<?php

namespace App\Http\Controllers;
use App\Models\Sale;
use App\Models\Book;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Exception;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Définir le nombre d'éléments par page
            $perPage = 10; 
    
            // Tenter de récupérer les livres depuis le cache
            $books = Cache::remember('books_page_' . $request->input('page', 1), now()->addMinutes(10), function () use ($perPage) {
                return Sale::paginate($perPage); // Récupérer les livres avec pagination
            });
    
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
         // Validation des données d'entrée
         $request->validate([

            'sale_date' => 'required|date',
            'paiement_type' => 'required|string',
            'status' => 'required|string',
            'books' => 'required|array',
            'books.*.book_id' => 'required|exists:books,id',
            'books.*.quantity' => 'required|integer|min:1',
            'books.*.type_book' => 'required|string',

        ]);

        
        try {
            // Créer la vente
            $sale = Sale::create([
                'sale_date' => $request->sale_date,
                'paiement_type' => $request->paiement_type,
                'total_price' => 0, // Cela sera mis à jour après
                'status' => $request->status,
            ]);

            // Calculer le prix total et ajouter les livres à la vente
            $totalPrice = 0;
            foreach ($request->books as $bookData) {
                $book = Book::find($bookData['book_id']);
                $quantity = $bookData['quantity'];
                $typeBook = $bookData['type_book'];

                // Calculer le prix total
                $totalPrice += $book->price * $quantity;

                // Attacher le livre à la vente avec les détails
                $sale->books()->attach($book->id, [
                    'quantity' => $quantity,
                    'total_price' => $book->price * $quantity,
                    'type_book' => $typeBook,
                ]);
            }

            // Mettre à jour le prix total de la vente
            $sale->total_price = $totalPrice;
            $sale->save();


            // Retourner une réponse JSON
            return response()->json(['message' => 'Commande passée avec succès!', 'sale' => $sale], 201);

        } catch (\Exception $e) {

            // Annuler la transaction en cas d'erreur
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
