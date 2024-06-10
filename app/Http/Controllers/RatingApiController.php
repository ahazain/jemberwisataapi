<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RatingApiController extends Controller
{
    public function __construct()
    {
        // Terapkan middleware hanya pada metode store, update, dan destroy
        $this->middleware('auth:api', ['except' => ['index', 'show', 'store']]);
    }

    public function index($id)
    {
        $ratings = Rating::where('wisata_id', $id)->get();
        return response()->json(['message' => 'Success', 'data' => $ratings]);
    }

    public function show($id, $rating_id)
    {
        $rating = Rating::where('wisata_id', $id)->where('id', $rating_id)->firstOrFail();
        return response()->json(['message' => 'Success', 'data' => $rating]);
    }

    public function store(Request $request, $id)
    {
        Log::info('User ID: ' . auth()->user());

        $validatedData = $request->validate([
            'rating_value' => 'required|integer|min:1|max:5',
        ]);

        $validatedData['wisata_id'] = $id;
        $validatedData['user_id'] = auth()->id();
        $validatedData['id_admin'] = Auth::id();

        // Log::info('Validated Data: ', $validatedData); // Tambahkan ini untuk debugging json(auth()->user());

        $rating = Rating::create($validatedData);

        return response()->json(['message' => 'Rating berhasil ditambahkan', 'data' => $rating], 201);
    }


    public function update(Request $request, $id, $rating_id)
    {
        $validatedData = $request->validate([
            'rating_value' => 'required|integer|min:1|max:5',
        ]);

        $rating = Rating::where('wisata_id', $id)->where('id', $rating_id)->firstOrFail();
        $rating->update($validatedData);

        return response()->json(['message' => 'Rating berhasil diperbarui', 'data' => $rating]);
    }

    public function destroy($id, $rating_id)
    {
        $rating = Rating::where('wisata_id', $id)->where('id', $rating_id)->firstOrFail();
        $rating->delete();

        return response()->json(['message' => 'Rating berhasil dihapus']);
    }
}
