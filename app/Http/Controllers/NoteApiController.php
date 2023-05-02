<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use Illuminate\Support\Facades\Validator; //import validator


class NoteApiController extends Controller
{

    public function index()
    {
        $notes = Note::all();
        return response()->json($notes);
    }

    public function show($id)
    {
        $note = Note::where('id', $id)->get();
        return response()->json($note);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required', 'max:100',
            'description' => 'max:1000',
        ]);

        // $validatedData['user_id'] = auth()->user()->id;
        $note = new Note();
        $note->user_id = $request->user_id;
        $note->title = $request->title;
        $note->description = $request->description;
        $note->save();
        return response()->json('masukk');
    }

    public function update(Request $request)
    {
        $note = $request->validate([
            'title' => 'required', 'max:100',
            'description' => 'max:1000',
        ]);
        Note::where('id', $request->id)->update($note);
        return response()->json('masukk');
    }

    public function delete($id)
    {
        Note::destroy($id);
        return response()->json();
    }
}
