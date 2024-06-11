<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventApiController extends Controller
{
    public function index() {
        $events = Event::all();
        return response()->json(['message'=> 'success', 'data' => $events]);
    }
    public function store(Request $request) {
        $validatedData = $request->validate([
            'nama_acara' => 'required|string|max:100',
            'gambar' => 'required|url|max:500',
            'deskripsi' => 'nullable|string',
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        $validatedData['id_admin'] = Auth::id();
        $event = Event::create($validatedData);
    
        return response()->json(['message' => 'Data acara berhasil ditambahkan', 'data' => $event], 200);
    }
    
    public function update(Request $request, $id) {
        $validatedData = $request->validate([
            'nama_acara' => 'string|max:100',
            'gambar' => 'url|max:500',
            'deskripsi' => 'nullable|string',
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);
        $validatedData['id_admin'] = Auth::id();
        $event = Event::findOrFail($id);
        $event->update($validatedData);

    
        return response()->json(['message' => 'Data acara berhasil diperbarui', 'data' => $event], 200);
    }
    
    public function destroy($id) {
        try {
            // Ambil ID pengguna dari token atau sesi
            $userId = auth()->id();
            
            // Cari data wisata berdasarkan ID
            $wisata = Event::findOrFail($id);
            
            // Hapus data wisata
            $wisata->delete();
         
            return response()->json(['message' => 'Data acara berhasil dihapus']);
        } catch (\Exception $e) {
            // Tangani jika ada kesalahan saat menghapus data
            return response()->json(['error' => 'Gagal menghapus data acara: ' . $e->getMessage()], 500);
        }
    }
}
