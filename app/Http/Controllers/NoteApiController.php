<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Note;

class NoteApiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $user = Auth::user();
        $notes = Note::where('user_id', $user->id)->get();
        return response()->json(['notes' => $notes->sortBy('created_at')]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $user = Auth::user();
        $note = new Note();
        $note->user_id = $user->id;
        $note->title = $request->title;
        $note->description = $request->description;
        $note->save();
        return response()->json(['message' => 'Note created!']);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $note = Note::find($request->id);
        //cek user
        if ($user->id != $note->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        //validasi data masuk
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);
        //bila gagal validasi
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        //update note
        Note::where('id', $request->id)->update($request->all());

        return response()->json(['message' => 'Note updated!']);
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        $note = Note::find($request->id);
        // cek user
        if ($user->id != $note->user_id) {
            return response()->json(['error' => 'Unauthorized']);
        }
        Note::where('id', $request->id)->delete();
        return response()->json(['message' => 'Note deleted!']);
    }
}
