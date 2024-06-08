<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

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
    
        $event = Event::create($validatedData);
    
        return response()->json(['message' => 'Data acara berhasil ditambahkan', 'data' => $event], 201);
    }
    
    public function update(Request $request, $id) {
        $validatedData = $request->validate([
            'nama_acara' => 'required|string|max:100',
            'gambar' => 'required|url|max:500',
            'deskripsi' => 'nullable|string',
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);
    
        $event = Event::findOrFail($id);
        $event->update($validatedData);
    
        return response()->json(['message' => 'Data acara berhasil diperbarui', 'data' => $event], 200);
    }
    
    public function destroy($id) {
        $event = Event::findOrFail($id);
        $event->delete();
    
        return response()->json(['message' => 'Data acara berhasil dihapus']);
    }
    
}
