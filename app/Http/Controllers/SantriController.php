<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SantriController extends Controller
{
    public function index()
    {
        $santri = Santri::all();
        return response()->json(['data' => $santri], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'fullname'              => 'required|string|unique:santri|min:5|max:20',
            'nisn'                  => 'required|max:10|unique:santri',
            'birth_date'            => 'nullable|date',
            'email'                 => 'required|email|unique:santri',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'photoName'             => 'required',
            'photo'                 => 'nullable|image|file|mimes:jpeg,png,jpg',
        ]);
        if ($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            // Nama file yang disimpan sesuai dengan fotoName
            $filename = $request->photoName;
            // Menyimpan foto dengan nama file yang telah ditentukan
            $photo->storeAs('images/santri/profil/', $filename, 'public');
        }

        $santri = new Santri;
        $santri->fullname    = $request->fullname;
        $santri->nisn        = $request->nisn;
        $santri->birth_date  = $request->birth_date;
        $santri->photo       = $request->photoName;
        $santri->email       = $request->email;
        $santri->password    = Hash::make($request->password);
        $santri->role_id     = 3;
        $santri->save();

        $response = [
            'message'   => 'Successfully created',
            'data'      => [
                'fullname'  => $santri->fullname,
                'email'     => $santri->email,
            ],
        ];
        return response()->json($response, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $santri = Santri::find($id);

        if (!$santri) {
            return response()->json(['error' => 'Data santri not found'], Response::HTTP_NOT_FOUND);
        }

        $santriData = [
            'id'                => $santri->id,
            'fullname'          => $santri->fullname,
            'nisn'              => $santri->nisn,
            'photo'             => $santri->photo,
            'email'             => $santri->email,
            'created_at'        => $santri->created_at,
            'updated_at'        => $santri->updated_at,
        ];

        return response()->json(['data' => $santriData], Response::HTTP_OK);
    }

    public function getImageSantri($id)
    {
        $santri = Santri::findOrFail($id);

        return response()->file(public_path("storage/images/santri/profil/$santri->photo"));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(request()->all(), [
            'fullname'              => 'required|string|min:5|max:20',
            'nisn'                  => 'required|max:10',
            'photo'                 => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048',
            'birth_date'            => 'required|date',
            'email'                 => 'required|email',
            'password'              => 'nullable|string|min:8',
        ]);
        if ($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }

        $santri = Santri::findOrFail($id);
        if ($request->hasFile('photo')) {
            $file               = $request->file('photo');
            $filename           = $file->getClientOriginalName();
            $extension          = $file->getClientOriginalExtension();
            $filenameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
            $filenameToStore    = $filenameWithoutExt . '_' . date('YmdHi') . '.' . $extension;

            if (!$file->move(public_path('storage/images/santri/profil/'), $filenameToStore)) {
                return response()->json(['error' => 'Gagal mengunggah gambar.'], 400);
            }
            // hapus file gambar terdahulu jika ada dan jika pengunggahan file baru berhasil
            if ($santri->photo && file_exists(public_path('storage/images/santri/profil/' . $santri->photo))) {
                unlink(public_path('storage/images/santri/profil/' . $santri->photo));
            }
            $santri->photo = $filenameToStore;
        } else {
            if (!empty($santri->photo) && file_exists(public_path('storage/images/santri/profil/' . $santri->photo))) {
                unlink(public_path('storage/images/santri/profil/' . $santri->photo));
            }
            $santri->photo = null;
        }
        $santri->fullname    = $request->fullname;
        $santri->nisn        = $request->nisn;
        $santri->birth_date  = Carbon::parse($request->birth_date)->format('Y-m-d');
        $santri->email       = $request->email;
        $santri->role_id     = 3;
        if (!empty($request->password)) {
            $santri->password = Hash::make($request->password);
        }
        $santri->save();

        $response = [
            'message'   => 'Successfully updated',
            'data'      => [
                'fullname'  => $santri->fullname,
                'email'     => $santri->email,
            ],
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $santri = Santri::findOrFail($id);
        if (!$santri) {
            return response()->json(['error' => 'Data santri not found'], 404);
        }

        if (!empty($santri->photo) && file_exists(public_path('storage/images/santri/profil/' . $santri->photo))) {
            unlink(public_path('storage/images/santri/profil/' . $santri->photo));
        }

        $santri->tokens->each(function ($token) {
            $token->delete();
        });

        $santri->delete();

        return response()->json(['message' => 'Successfully deleted']);
    }
}
