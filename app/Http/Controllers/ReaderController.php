<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reader;
use Illuminate\Support\Facades\Log;

class ReaderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $readers = Reader::orderBy('created_at', 'desc')->get();
        return response()->json($readers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    
        $request->validate([
            'email' => 'required|email|unique:readers,email',
            'name' => 'string',
            'phone' => 'string',
        ]);

        try{
          
        $reader = Reader::create($request->all());
        Log::info($request->all());
        return response()->json($reader, 201);
    } catch (\Exception $e) {
        // Retourner une réponse JSON avec le message d'erreur
        return response()->json([
            'error' => $e->getMessage(),
        ], 400);
    }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reader = Reader::findOrFail($id);
        return response()->json($reader);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $reader = Reader::findOrFail($id);
        $request->validate([
            'email' => 'required|email|unique:readers,email,' . $reader->id,
            'name' => 'string',
            'phone' => 'string',
        ]);

        $reader->update($request->all());
        return response()->json($reader);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reader = Reader::findOrFail($id);
        $reader->delete();
        return response()->json(null, 204);
    }
}
