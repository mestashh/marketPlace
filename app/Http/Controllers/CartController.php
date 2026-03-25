<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use Illuminate\Http\Request;

class CartController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $cart = $request->user()->cart;
        $this->authorize('view', $cart);

        return new CartResource($cart);
    }

}
