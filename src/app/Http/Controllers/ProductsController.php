<?php

namespace App\Http\Controllers;

use App\Helpers\Response\ResponseAPI;
use App\Models\Product;
use App\Transformers\ProductTransformer;
use Illuminate\Http\JsonResponse;

class ProductsController extends Controller
{

    public function index(): JsonResponse
    {
        return ResponseAPI::results(new ProductTransformer(Product::all()->toArray(), 'collection'));
    }

}
