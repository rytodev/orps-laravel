<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Lokasi;

class LokasiApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $user = Auth::user();
        $lokasis = Lokasi::where('user_id', $user->id)->get();

        foreach ($lokasis as $lokasi) {
            $foto = null;
            if ($lokasi->foto) {
                $foto = Storage::disk('public')->get("foto_tps/{$lokasi->foto}");
                $foto = base64_encode($foto);
            }
            $lokasi->foto = $foto;
        }
        return response()->json($lokasis);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'alamat' => 'required',
            'long' => 'required',
            'lat' => 'required',
            'fotoName' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 500);
        }

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            // Nama file yang disimpan sesuai dengan fotoName
            $filename = $request->fotoName;
            // Menyimpan foto dengan nama file yang telah ditentukan
            $foto->storeAs('foto_tps', $filename, 'public');
        }

        $lokasi = new Lokasi();
        $lokasi->user_id = Auth::user()->id;
        $lokasi->name = $request->name;
        $lokasi->alamat = $request->alamat;
        $lokasi->lng = $request->long;
        $lokasi->lat = $request->lat;
        $lokasi->foto = $request->fotoName;
        $lokasi->save();
        return response()->json(['message' => 'Lokasi created!', 'data' => $lokasi]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'alamat' => 'required',
            'long' => 'required',
            'lat' => 'required',
            'fotoName' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 500);
        }

        $lokasi = Lokasi::find($request->id);
        if (!$lokasi) {
            return response()->json(['error' => 'Lokasi not found'], 404);
        }

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            // Nama file yang disimpan sesuai dengan fotoName
            $filename = $request->fotoName;
            // Menghapus foto yang sudah ada sebelumnya
            Storage::disk('public')->delete("foto_tps/{$lokasi->foto}");
            // Menyimpan foto dengan nama file yang telah ditentukan
            $foto->storeAs('foto_tps', $filename, 'public');
            $lokasi->foto = $filename;
        }

        $lokasi->name = $request->name;
        $lokasi->alamat = $request->alamat;
        $lokasi->lng = $request->long;
        $lokasi->lat = $request->lat;
        $lokasi->save();

        return response()->json(['message' => 'Lokasi updated!']);
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        $lokasi = Lokasi::find($request->id);

        if ($user->id != $lokasi->user_id) {
            return response()->json(['error' => 'Unauthorized']);
        }
        Lokasi::where('id', $request->id)->delete();
        return response()->json(['message' => 'Lokasi deleted!']);
    }
}
