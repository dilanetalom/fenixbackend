<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\Validator;
use Exception;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Récupérer tous les actualites  
       $news = News::all();

       // Retourner les actualites  en JSON
       return response()->json($news);

       } catch (Exception $e) {
           // Retourner une réponse JSON avec le message d'erreur
           return response()->json([
               'error' => $e->getMessage(),
           ], 400);
       }

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
        $validator = Validator::make($request->all(), [
            'name'=> 'required|string|max:255',
            'description'=> 'required|string|max:255',
            'newsdate'=> 'required|string|max:255',
            'user_id'=> 'required|string|max:255',
          
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {

            // enregistrer une actualite

            $news = News::create([
                'name'=> $request->name,
                'description'=> $request->description,
                'newsdate'=> $request->newsdate,
                'user_id'=> $request->user_id,
            ]);
              
            return response()->json([
                'message' => 'Actualite enregistré avec succès.',
                'news' => $news,
            ]);

       } catch (Exception $e) {
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
        try {
            // Récupérer une actualite 
       $news = News::find($id);

       // Retourner les details de l actualite
       return response()->json($news);

       } catch (Exception $e) {
           // Retourner une réponse JSON avec le message d'erreur
           return response()->json([
               'error' => $e->getMessage(),
           ], 400);
       }
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
        try {
            // Trouver l'actualite par son ID
            $news = News::findOrFail($id);
    
            // Mettre à jour les attributs de l'actualite
            $news->update([
                'name'=> $request->name,
                'description'=> $request->description,
                'newsdate'=> $request->newsdate,
                'user_id'=> $request->user_id,
            ]);
    
            return response()->json([
                'message' => 'actualite modifié avec succès',
                'event' => $news,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Trouver l'actualite par son ID
            $event = News::findOrFail($id);
    
            // Supprimer l actualite
            $event->delete();
    
            return response()->json([
                'message' => 'actualite supprimé avec succès.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'actualite non trouvé ou erreur lors de la suppression.',
            ], 404);
        }
    }

    // MyApp Personal Access Client
}
