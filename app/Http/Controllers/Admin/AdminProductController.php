<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    public function index()
    {
        $viewData = [
            "title" => "Admin Page - Products - Online Store",
            "products" => Product::all(),
        ];
        return view('admin.product.index', ['viewData' => $viewData]);
    }

    public function store(Request $request)
    {
        Product::validate($request);

        $newProduct = new Product();
        $newProduct->setName($request->input('name'));
        $newProduct->setDescription($request->input('description'));
        $newProduct->setPrice($request->input('price'));
        $newProduct->setImage("game.png");

        if ($request->hasFile('image')) {
            $imageName = $newProduct->getId() . "." . $request->file('image')->extension();
            Storage::disk('public')->put(
                $imageName,
                file_get_contents($request->file('image')->getRealPath())
            );
            $newProduct->setImage($imageName);
        }

        $newProduct->save();

        return back();
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return back();
    }

    public function edit($id)
    {
        $viewData = [
            'title' => "Admin Page - Edit Product - Online Store",
            'product' => Product::findOrFail($id),
        ];

        return view('admin.product.edit', ['viewData' => $viewData]);
    }

    public function update(Request $request, $id)
    {
        Product::validate($request);

        $product = Product::findOrFail($id);
        $product->setName($request->input('name'));
        $product->setDescription($request->input('description'));
        $product->setPrice($request->input('price'));

        if ($request->hasFile('image')) {
            $imageName = $id . "." . $request->file('image')->extension();
            Storage::disk('public')->put(
                $imageName,
                file_get_contents($request->file('image')->getRealPath())
            );

            $product->setImage($imageName);
        }

        $product->save();

        return redirect()->route('admin.product.index');
    }
}