<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $viewData = [
            "title" => "Products - Online Store",
            "subtitle" => "List of Products",
            "products" => Product::all(),
        ];
        return view('product.index', ['viewData' => $viewData]);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $viewData = [
            "title" => $product->getName() . " - Online Store",
            "subtitle" => $product->getName() . " Product Details",
            "product" => $product,
        ];
        return view('product.show', ['viewData' => $viewData]);
    }
}