<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Invoice::all()->load('user');
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
    public function store(StoreInvoiceRequest $request)
    {
        $validated=$request->validated();
        $userId=$request->user->id;
        $validated['user_id']=$userId;
        $validated['amount']=0;
        $products=$validated['products'];
        foreach ($products as $index=>$product) {
            $getProduct=Product::findOrFail($product['id']);
            $price=$getProduct['price']*$product['quantity'];
            $validated['amount']+=$price;
            $products[$index]['price']=$price;
        }
        $invoice = Invoice::create($validated);
        
        foreach ($products as $product) {
            $invoice->products()->attach(
                $product['id'],[
                    "quantity"=>$product['quantity'],
                    "price"=>$product['price']
                ]
            );
        }
        return response()->json($invoice->load('products'),201);
    }

    public function getUserInvoicesByDate(Request $request)
    {
        $c=$request->user;
        try {
            $userId=$request->user->id;
            $invoices=Invoice::with("user")->where("user_id",$userId)->get()->groupBy(function ($invoice){
            return $invoice->created_at->format('d-m-Y');
            });

            if($invoices->isEmpty())
            {
                return response()->json(["error"=>"No invoices found for this user"],404);
            }

            return response()->json($invoices, 200);
        } catch (Exception $e) {
            return response()->json(["error"=>$e->getMessage()],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {

        $invoice->load(['user']);

        return response()->json($invoice,200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
