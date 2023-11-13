<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Routing\Controller;
use App\Http\Requests\ValidateCheckoutRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\CheckoutMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrdersController extends Controller
{
    public function viewOrders(Request $request)
    {
        $data = Order::with('products')->get();
        
        return response()->json($data);
    }

    public function viewOrder(Request $request, $id)
    {
        $order = Order::with('products')->findOrFail($id);

        return $request->ajax() ? response()->json($order) : view('order', ['order' => $order]);
    }

    public function checkout(Request $request)
    {
        $idProductsInCart = session()->get('cart');
        $cartQuantity = session()->get('cartQuantity');
        $products = Product::whereIn('id', $idProductsInCart)->get();
       
        if ($products->isEmpty()) {
            throw ValidationException::withMessages([
                'cartError' => [trans('messages.error')],
            ])->status(422);
        }
        try {
            $toMail = $request['mail'];
            Mail::to(config('credentialsAdmin.adminEmail'))->send(new CheckoutMail($products, $toMail));

            // insert order table
            $order = new Order;
            $order->date = now();
            $order->name = $request['name'];
            $order->contactDetails = $request['mail'];
            $order->comments = $request['comments'];
            $order->save();

            // insert pivot table

            $cartQuantityMaped = array_map('current', $cartQuantity);
            foreach ($cartQuantityMaped as $items => $quantity) {
                $value = $quantity;
                $priceProduct= Product::findOrFail($idProductsInCart[$items]);
                $order->products()->attach([$idProductsInCart[$items] => ['quantity' => $value, 'checkout_price' => $priceProduct->price]]);
            }
        } catch (\Exception $err) {
            Log::error($err);
            throw ValidationException::withMessages([
                'cart' => [trans('messages.error')]
            ])->status(422);
        }
        session()->forget('cartQuantity');
        session()->forget('cart');
    }

    public function productsView(Request $request)
    {
        $products = Product::all();
        return response()->json($products);
    }
}
