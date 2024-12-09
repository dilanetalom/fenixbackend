<?php

namespace App\Http\Controllers;
use App\Models\Sale;
use App\Models\Book;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            $sales = Sale::with('book')->orderBy('created_at', 'desc')->get();
            return response()->json($sales);
     
    
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
        Log::info($request);
        $request->validate([
            'sale_date' => 'required|date',
            'paiement_type' => 'required|string|max:255',
            'total_price' => 'required|numeric',
            'user_id' => 'required|numeric',
            'status' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'type_book' => 'required|string|max:255',
            'book_id' => 'required|exists:books,id', // Assurez-vous que l'ID du livre existe
        ]);

        
        try {
            $sale = Sale::create($request->all());
            return response()->json($sale, 201);
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
        try{
        $sale = Sale::findOrFail($id);
        return response()->json($sale);
    } catch (\Exception $e) {

        // Annuler la transaction en cas d'erreur
        return response()->json(['error' => $e->getMessage()], 400);
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
        try{
        $request->validate([
            'sale_date' => 'required|date',
            'paiement_type' => 'string|max:255',
            'total_price' => 'numeric',
            'status' => 'string|max:255',
            'quantity' => 'integer',
            'type_book' => 'string|max:255',
            'book_id' => 'exists:books,id',
        ]);
        $sale = Sale::findOrFail($id);
        $sale->update($request->all());
        return response()->json($sale);
    }
        catch (\Exception $e) {

            // Annuler la transaction en cas d'erreur
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
        $sale = Sale::findOrFail($id);
        $sale->delete();
        return response()->json(null, 204);
    }
    catch (\Exception $e) {

        // Annuler la transaction en cas d'erreur
        return response()->json(['error' => $e->getMessage()], 400);
    }
   }

 
}
