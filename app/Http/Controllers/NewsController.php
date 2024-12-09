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
       $news = News::orderBy('created_at', 'desc')->get();

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
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'frome' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'newsdate' => 'required|date',
            'user_id' => 'required|integer',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validation de l'image
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
        try {
            // Enregistrer l'image
            if ($request->hasFile('image')) {
                $images = $request->file('image');
                $imageName = time() . '_' . $images->getClientOriginalName();
                $images->move(public_path('images/news'), $imageName);
               
            } else {
                return response()->json(['error' => 'Image non fournie.'], 400);
            }
        
            // Enregistrer une actualité
            $news = News::create([
                'name' => $request->name,
                'description' => $request->description,
                'frome' => $request->frome,
                'type' => $request->type,
                'newsdate' => $request->newsdate,
                'user_id' => $request->user_id,
                'image' => $imageName, // Enregistrer le chemin de l'image
                'author_id' => $request->author_id, // Enregistrer le chemin de l'image
            ]);
              
            return response()->json([
                'message' => 'Actualité enregistrée avec succès.',
                'news' => $news,
            ], 201);
        
        } catch (Exception $e) {
            // Retourner une réponse JSON avec le message d'erreur
            return response()->json([
                'error' => 'Une erreur s\'est produite lors de l\'enregistrement de l\'actualité: ' . $e->getMessage(),
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
        
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'description' => 'required|string|max:1000',
            'frome' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'newsdate' => 'required|date',
            'user_id' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation de l'image (nullable)
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
        try {
            // Récupérer l'actualité à modifier
            $news = News::findOrFail($request->id); // Assurez-vous de passer l'ID de l'actualité dans la requête
        
            // Enregistrer l'image si un nouveau fichier est téléchargé
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image si elle existe
                if ($news->image) {
                    Storage::disk('public')->delete($news->image);
                }
                // Stocker la nouvelle image
                $imagePath = $request->file('image')->store('images/news', 'public');
                $news->image = $imagePath; // Mettre à jour le chemin de l'image
            }
        
            // Mettre à jour les autres champs
            $news->name = $request->name;
            $news->description = $request->description;
            $news->frome = $request->frome;
            $news->type = $request->type;
            $news->newsdate = $request->newsdate;
            $news->user_id = $request->user_id;
            $news->author_id = $request->author_id;
            
            // Enregistrer les modifications
            $news->save();
              
            return response()->json([
                'message' => 'Actualité modifiée avec succès.',
                'news' => $news,
            ], 200);
        
        } catch (Exception $e) {
            // Retourner une réponse JSON avec le message d'erreur
            return response()->json([
                'error' => 'Une erreur s\'est produite lors de la modification de l\'actualité: ' . $e->getMessage(),
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
