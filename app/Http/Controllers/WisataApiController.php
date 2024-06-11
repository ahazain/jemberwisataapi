<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wisata;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
     
     public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_wisata' => 'required|string|max:100',
            'gambar' => 'required|url|max:500',
            'jenis_wisata_id' => 'required|integer|exists:jenis_wisata,id',
            'deskripsi' => 'nullable|string',
            'alamat' => 'nullable|string|max:255',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            
        ]);

        // Menambahkan ID pengguna (admin) ke dalam data yang divalidasi
        $validatedData['id_admin'] = Auth::id();

       $wisata = Wisata::create($validatedData);

        return response()->json(['message' => 'Data Wisata berhasil ditambahkan', 'data' => $wisata], 200);
    }

    
 
     public function update(Request $request, $id) {
        
         $validatedData = $request->validate([
            'nama_wisata' => 'string|max:100',
            'gambar' => 'url|max:500',
            'jenis_wisata_id' => 'integer|exists:jenis_wisata,id',
            'deskripsi' => 'nullable|string',
            'alamat' => 'nullable|string|max:255',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
             
         ]);
         $validatedData['id_admin'] = Auth::id();
         $wisata = Wisata::findOrFail($id);
         $wisata->update($validatedData);
         
     
         return response()->json(['message' => 'Data wisata berhasil diperbarui', 'data' => $wisata], 201);
     }
 
     public function destroy($id) {
        try {
            // Ambil ID pengguna dari token atau sesi
            $userId = auth()->id();
            
            // Cari data wisata berdasarkan ID
            $wisata = Wisata::findOrFail($id);
            
            // Hapus data wisata
            $wisata->delete();
         
            return response()->json(['message' => 'Data wisata berhasil dihapus']);
        } catch (\Exception $e) {
            // Tangani jika ada kesalahan saat menghapus data
            return response()->json(['error' => 'Gagal menghapus data acara: ' . $e->getMessage()], 500);
        }
    }
    
     
}
