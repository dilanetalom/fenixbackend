<?php

namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Author;
use App\Models\News;
use App\Models\Event;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
           
            // Tenter de récupérer les livres depuis le cache
            $books = Book::orderBy('created_at', 'desc')->get(); // Récupérer les livres 
           
    
            // Retourner les livres en JSON
            return response()->json($books);
    
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
    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
     
           $validator = Validator::make($request->all(), [
            'title'=> 'required|string|max:255',
            'description'=> 'required|string|max:255',
            'category'=> 'required|string|max:255',
            'language'=> 'string|max:255',          
            'status'=> 'string|max:255',
            'niveau'=> 'string|max:255',
            'pub_date'=> 'required|string|max:255',
            'price_n'=> 'required|string|max:255',
            'price_p'=> 'required|string|max:255',
            'user_id'=> 'required|string|max:255',
            'name'=> 'required|string|max:255',
            'gender'=> 'required|string|max:255',
            'author_id'=> 'required|integer',
            'country'=> 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 400);
        }

        try {
 
            $images = $request->file('image');
            $imageName = time() . '_' . $images->getClientOriginalName();
            $images->move(public_path('images/books'), $imageName);
            // Enregistrer le livre dans la base de données avec l'image
            $book = Book::create([
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'language' => $request->language,
                'image' => $imageName, // Chemin de l'image enregistré
                'status' => $request->status,
                'niveau' => $request->niveau,
                'pub_date' => $request->pub_date,
                'price_n' => $request->price_n,
                'price_p' => $request->price_p,
                'user_id' => $request->user_id,
                'author_id' => $request->author_id, // Vous avez déjà l'auteur ici
            ]);

            return response()->json(['message' => 'Book created successfully', 'book' => $book], 201);
        // }

        // return response()->json(['error' => 'File not uploaded'], 400);


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
            // Récupérer un livre 
       $book = Book::find($id);

       // Retourner les details du livre
       return response()->json($book);

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
        try {
            // Récupérer les livres d un auteur
       $book = Book::where('author_id',$id)->get();
       return response()->json($book);

       } catch (Exception $e) {
           // Retourner une réponse JSON avec le message d'erreur
           return response()->json([
               'error' => $e->getMessage(),
           ], 400);
       }
    }

    /**
     * Update the specified resource in storage.
     */

// Fonction pour mettre à jour un livre

    public function update(Request $request, string $id)
    {
        // Log::info($request->all());
        //  Valider les champs et l'image
        //  $request->validate([
        //     'title' => 'string|max:255',
        //     'description' => 'string',
        //     'category' => 'string',
        //     'language' => 'string',
        //     'status' => 'string',
        //     'niveau' => 'string',
        //     'pub_date' => 'date',
        //     'price_n' => 'numeric',
        //     'price_p' => 'numeric',
        //     'user_id' => 'integer',
        //     'author_id' => 'integer',
        // ]);


        // Trouver le livre à mettre à jour
        $book = Book::findOrFail($id);
        Log::info($book);
      
        try {
           

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/books'), $imageName);

            // Supprimer l'ancienne image si elle existe
            if ($book->image) {
                $oldImagePath = public_path($book->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Mettre à jour le chemin de la nouvelle image
            $book->image =  $imageName;
        }

        // Mettre à jour les autres champs
  
        $book->title = $request->title;
        $book->description = $request->description;
        $book->category = $request->category;
        $book->language = $request->language;
        $book->status = $request->status;
        $book->niveau = $request->niveau;
        $book->pub_date = $request->pub_date;
        $book->price_n = $request->price_n;
        $book->price_p = $request->price_p;
        $book->user_id = $request->user_id;
        $book->author_id = $request->author_id;

        // Enregistrer les modifications
        $book->save();

      
        return response()->json([
            'message' => 'Book updated successfully', 
            'book' => $book
        ], 200);
    } catch (Exception $e) {
        Log::error('Erreur de modification : ' . $e->getMessage());
        return response()->json([
            'error' => 'Erreur de modification: ' . $e->getMessage(),
        ], 500);

        }
    }

    /**
     * Remove the specified resource from storage.
     */

     // Fonction pour supprimer un livre

    public function destroy(string $id)
    {
        try {

            // Trouver le livre par son ID
            $book = Book::findOrFail($id);
            if ($book->image) {
                $oldImagePath = public_path($book->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            // Supprimer le livre
            $book->delete();
    
            return response()->json([
                'message' => 'Livre supprimé avec succès.',
            ]);


        } catch (Exception $e) {
            return response()->json([
                'error' => 'Livre non trouvé ou erreur lors de la suppression.',
            ], 404);
        }
    }

// 


// Fonction pour supprimer un auteur

    public function deleteauthor(string $id)
    {
        try {
            // Trouver le livre par son ID
            $author = Author::findOrFail($id);
            $author->books()->delete();
            if ($author->imageauthor) {
                $oldImagePath = public_path($author->imageauthor);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            // Supprimer le livre
            $author->delete();
    
            return response()->json([
                 
            ]);
        } catch (Exception $e) {
        Log::error('Erreur de suppression : ' . $e->getMessage());
        return response()->json([
            'error' => 'Erreur de suppression: ' . $e->getMessage(),
        ], 500);

        }
    }

// 



    public function filterBooks(Request $request)
{
    try {
        // Commencer la requête de base
        $query = Book::query();

        // Filtrer par les critères fournis dans la requête
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('pub_date')) {
            $query->where('pub_date', $request->pub_date);
        }
        if ($request->filled('price')) {
            $query->where('price', $request->price);
        }

        if ($request->filled('author_id')) {
            $query->where('author_id', $request->author_id);
        }

        // Exécuter la requête et récupérer les livres filtrés
        $books = $query->get();

        return response()->json($books);
    } catch (Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
        ], 400);
    }
}


public function saveauthor(Request $request)
{
    // Validation des données entrantes
    // $validator = Validator::make($request->all(), [
    //     'name' => 'required|string|max:255',
    //     'gender' => 'nullable|string|max:10',
    //     'country' => 'nullable|string|max:100',
    //     'imageauthor' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     'description' => 'nullable|string',
    //     'date_nais' => 'nullable|date',
    //     'email' => 'nullable|email|unique:authors,email',
    // ]);

    // if ($validator->fails()) {
    //     return response()->json($validator->errors(), 422);
    // }

    // Gérer le téléchargement de l'image
 
    $file = $request->file('imageauthor');
        
    // Générez un nom unique pour le fichier
    $fileName = time() . '_' . $file->getClientOriginalName();
    
    // Déplacez le fichier dans le dossier de stockage
    $file->move(public_path('images/authors'), $fileName);
    // Créer un nouvel auteur

    $author = Author::create([
        'name' => $request->name,
        'gender' => $request->gender,
        'country' => $request->country,
        'imageauthor' => $fileName, // Enregistrer le chemin de l'image
        'description' => $request->description,
        'date_nais' => $request->date_nais,
        'email' => $request->email,
    ]);

    return response()->json($author, 201);
}



public function updateAuthor(Request $request, $id)
{
    

    // Find the author to update
    $author = Author::findOrFail($id);

    // Update the author's attributes
    $author->update([
        'name' => $request->name,
        // ... other fields to update
    ]);

    // Handle image update (optional)
    if ($request->hasFile('imageauthor')) {
        // Delete the old image if it exists
        if ($author->imageauthor) {
            Storage::delete('public/images/authors/' . $author->imageauthor);
        }

        // Upload the new image
        $file = $request->file('imageauthor');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images/authors'), $fileName);

        // Update the author's image path
        $author->imageauthor = $fileName;
        $author->save();
    }

    return response()->json($author, 200);
}



public function getbyauthor(string $id)
{
    try {
         // Récupérer tous les auteurs 
    $author = Author::find($id);

    // Retourner les auteurs  en JSON
    return response()->json($author);

    } catch (Exception $e) {
        // Retourner une réponse JSON avec le message d'erreur
        return response()->json([
            'error' => $e->getMessage(),
        ], 400);
    }
  
}


public function allauthor()
{
    try {
         // Récupérer tous les auteurs 
    $author = Author::with('books')->orderBy('created_at', 'desc')->get();

    // Retourner les auteurs  en JSON
    return response()->json($author);

    } catch (Exception $e) {
        // Retourner une réponse JSON avec le message d'erreur
        return response()->json([
            'error' => $e->getMessage(),
        ], 400);
    }
  
}


public function allbookbyuser(String $id)
{
    try {
         // Récupérer tous les livres enregistrés par un utilisateur précis 
    $allbookuser = Book::where('id', $id)->get();

    // Retourner les livres  en JSON
    return response()->json($allbookuser);

    } catch (Exception $e) {
        // Retourner une réponse JSON avec le message d'erreur
        return response()->json([
            'error' => $e->getMessage(),
        ], 400);
    }
  
}

public function count()
{
    // Compter le nombre d'auteurs
    $authorsCount = Author::count();

    // Compter le nombre de livres
    $booksCount = Book::count();

    // Compter le nombre d'actualités
    $newsCount = News::count();

    $eventCount = Event::count();

    // Retourner les résultats
    return response()->json([
        'authors_count' => $authorsCount,
        'books_count' => $booksCount,
        'news_count' => $newsCount,
        'event_count' => $eventCount,
    ]);
  
}


}
