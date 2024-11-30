w<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;
use Exception;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Récupérer tous les evennements 
       $event = Event::all();

       // Retourner les evennements  en JSON
       return response()->json($event);

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
            'eventdate'=> 'required|string|max:255',
            'image'=> 'required|string|max:255',
            'user_id'=> 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        try {


                   // Gérer l'upload de l'image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/event'), $imageName);

            // Enregistrer le livre dans la base de données avec l'image
            
            $event = Event::create([
                'name'=> $request->name,
                'description'=> $request->description,
                'eventdate'=> $request->eventdate,
                'image'=>'images/event'. $imageName ,
                'user_id'=> $request->user_id,
            ]);

            return response()->json(['message' => 'Book created successfully', 'event' => $event], 201);
        }

        return response()->json(['error' => 'File not uploaded'], 400);


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
            // Récupérer un evennement 
       $event = Event::find($id);

       // Retourner les details de l evennement
       return response()->json($event);

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
            // Trouver le evennement par son ID
            $event = Event::findOrFail($id);

            if ($request->hasFile('image')) {
                $images = $request->file('image');
                $image = time() . '_' . $images->getClientOriginalName();
                $images->move(public_path('images/event'), $image);
    
                // Supprimer l'ancienne image si elle existe
                if ($event->image) {
                    $oldImagePath = public_path($event->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
    
                // Mettre à jour le chemin de la nouvelle image        
                $event->image = 'images/event/' . $image;          
           
            }
    
            // Mettre à jour les attributs de l'evennement
            $event->update([
                'name'=> $request->name,
                'description'=> $request->description,
                'eventdate'=> $request->eventdate,
                'image'=>$request->image ,
                'user_id'=> $request->user_id,
            ]);
    
            return response()->json([
                'message' => 'evennement modifié avec succès',
                'event' => $event,
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
            // Trouver l'evennement par son ID
            $event = Event::findOrFail($id);

            if ($event->image) {
                $oldImagePath = public_path($event->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            // Supprimer l evennement
            $event->delete();
    
            return response()->json([
                'message' => 'evennement supprimé avec succès.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'evennement non trouvé ou erreur lors de la suppression.',
            ], 404);
        }
    }
}
