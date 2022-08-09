<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $total = 0;
        $productsInCart = [];

        $productsInSession = $request->session()->get("products");
        if ($productsInSession) {
            $productsInCart = Product::findMany(array_keys($productsInSession));
            $total = Product::sumPricesByQuantities($productsInCart, $productsInSession);
        }

        $viewData = [
            "title" => "Cart - OnlineStore",
            "subtitle" => "Shopping Cart",
            "total" => $total,
            "products" => $productsInCart,
        ];

        return view('cart.index', ['viewData' => $viewData]);
    }

    public function add(Request $request, $id)
    {
        $products = $request->session()->get("products");
        $products[$id] = $request->input('quantity');
        $request->session()->put("products", $products);

        return redirect()->route('cart.index');
    }

    public function delete(Request $request)
    {
        $request->session()->forget('products');
        return back();
    }

    public function purchase(Request $request)
    {
        $productsInSession = $request->session()->get("products");
        if ($productsInSession) {
            $order = new Order();
            $order->setUserId(Auth::user()->getId());
            $order->setTotal(0);
            $order->save();
            $total = 0;
            $productsInCart = Product::findMany(array_keys($productsInSession));
            foreach ($productsInCart as $product) {
                $quantity = $productsInSession[$product->getId()];
                $item = new Item();
                $item->setOrderId($order->getId());
                $item->setProductId($product->getId());
                $item->setQuantity($quantity);
                $item->setPrice($product->getPrice());
                $item->save();
                $total += $product->getPrice() * $quantity;
            }
            $order->setTotal($total);
            $order->save();

            $newBalance = Auth::user()->getBalance() - $total;
            Auth::user()->setBalance($newBalance);
            Auth::user()->save();

            $request->session()->forget('products');

            $viewData = [
                "title" => "Purchase - OnlineStore",
                "subtitle" => "Purchase Status",
                "order" => $order,
            ];

            return view('cart.purchase', ['viewData' => $viewData]);
        } else {
            return redirect()->route('cart.index');
        }
    }
}