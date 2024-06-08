<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wisata;

class WisataApiController extends Controller
{
    public function index() {
        $wisata = Wisata::all();
         return response()->json(['message'=> 'succes', 'data' => $wisata]);
     }
     public function show($id) {
         $wisata = Wisata::find($id);
         
         if (!$wisata) {
             return response()->json(['message'=> 'Data not found'], 404);
         }
         
         return response()->json(['message'=> 'Success', 'data' => $wisata]);
     }
     
     public function store(Request $request) {
         $validatedData = $request->validate([
             'nama_wisata' => 'required|string|max:100',
             'gambar' => 'required|url|max:500',
             'jenis_wisata_id' => 'required|integer|exists:jenis_wisata,id',
             'deskripsi' => 'nullable|string',
             'alamat' => 'nullable|string|max:255',
             // 'id_admin' => 'nullable|integer',
         ]);
     
         $wisata = Wisata::create($validatedData);
     
         return response()->json(['message' => 'Data wisata berhasil ditambahkan', 'data' => $wisata], 201);
     }
 
     public function update(Request $request, $id) {
         $validatedData = $request->validate([
             'nama_wisata' => 'required|string|max:100',
             'gambar' => 'required|url|max:500',
             'jenis_wisata_id' => 'required|integer|exists:jenis_wisata,id',
             'deskripsi' => 'nullable|string',
             'alamat' => 'nullable|string|max:255',
             // 'id_admin' => 'nullable|integer',
         ]);
     
         $wisata = Wisata::findOrFail($id);
         $wisata->update($validatedData);
     
         return response()->json(['message' => 'Data wisata berhasil diperbarui', 'data' => $wisata], 20);
     }
 
     public function destroy($id) {
         $wisata = Wisata::findOrFail($id);
         $wisata->delete();
     
         return response()->json(['message' => 'Data acara berhasil dihapus']);
     }
     
}
