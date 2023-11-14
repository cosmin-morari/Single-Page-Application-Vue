<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use App\Http\Requests\ValidateAddProduct;
use App\Http\Requests\ValidateQuantity;
use App\Http\Requests\ValidateEditProduct;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request)
    {
        $cartSession = session()->get('cart');
        $products = ($cartSession) ? Product::whereNotIn('id', $cartSession)->get() : Product::all();

        return response()->json($products);
    }

    public function cart()
    {

        $cartSession = session()->get('cart');
        $cartQuantity = session()->get('cartQuantity');
        if ($cartSession) {
            $products = Product::whereIn('id', $cartSession)->get();

            foreach ($products as $product) {
                $product->quantity = [...$cartQuantity];
            }

            if ($products) {
                return response()->json($products);
            }
        }
    }
    public function store($id)
    {
        $product = Product::findOrFail($id);

        if ($product) {
            if (!in_array($product->id, session('cart') ?? [])) {
                session()->push('cart', $product->id);
                $initialValue = '1';
                session()->push('cartQuantity', [$product->id => $initialValue]);
            }
        }

        return response()->json(session('cartQuantity'));
    }
    public function cartCheckout(Request $request, $id)
    {
        $quantity = $request->ajax() ? $request->input('updateValue') : $request->input('quantity');
        $cartQuantity = session('cartQuantity');
        $id = $request->ajax() ? $request->input('id') : $id;

        if ($request->input('setQuantity') || $request->ajax() && $request->input('action') === 'setQuantity') {
            foreach ($cartQuantity as $key => $value) {
                if (isset($value[$id])) {
                    $cartQuantity[$key][$id] = $quantity;
                }
            }
            session()->put('cartQuantity', $cartQuantity);
        }

        if ($request->input('delete') || $request->ajax() && $request->input('action') === 'delete') {
            $cartSession = session()->get('cart');
            $index = array_search($id, $cartSession);
            session()->forget("cart.$index");
            session()->forget("cartQuantity.$index");
        }

        return $request->ajax() ? response()->json(['success' => true]) : redirect()->back();
    }

    public function deleteProductCart($id)
    {
        $cartSession = session()->get('cart');
        $index = array_search($id, $cartSession);
        session()->forget("cart.$index");
        session()->forget("cartQuantity.$index");
        return response()->json(["success" => true]);
    }

    public function updateQuantity(Request $request, $id)
    {
        $quantity = $request[0];
        $cartQuantity = session('cartQuantity');
        foreach ($cartQuantity as $key => $value) {
            if (isset($value[$id])) {
                $cartQuantity[$key][$id] = $quantity;
            }
        }
        session()->put('cartQuantity', $cartQuantity);
        return response()->json(["success" => true]);
    }

    public function deleteProductFromDB($id)
    {
        Product::firstOrFail('id')->destroy($id);
        return response()->json(['message' => 'The product has been deleted.']);
    }

    public function update(Request $request)
    {
        $id = $request->ajax() ? $request->id : $request->id;
        $product = Product::findOrFail($id);

        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'price' => 'required|numeric',
                'category' => 'required|string',
                'file' => 'required|image'
            ]);

            if (!$validator->passes()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            } else {

                $title = $request->title;
                $description = $request->description;
                $price = $request->price;

                $image = $request->file('file');
                $imageExtension = $image->getClientOriginalExtension();
                $newImageName = time() . '-' . $request->title . '.' . $imageExtension;
                $image->move(public_path('storage/photos'), $newImageName);

                $data = [
                    'title' => $request->title,
                    'description' => $request->description,
                    'price' => $request->price,
                    'imageSource' => $newImageName
                ];

                $product->fill($data);
                $product->save();
                return response()->json(['message' => 'The product has been successfully updated.']);
            }
        } else {

            $title = $request->title;
            $description = $request->description;
            $price = $request->price;
            $newImageName = time() . '-' . $request->title . '.' . $request->image->extension();
            $request->image->move(public_path('storage/photos'), $newImageName);
            $data = [
                'title' => $title,
                'description' => $description,
                'price' => $price,
                'category' => $request->category,
                'imageSource' => $newImageName
            ];
            $product->fill($data);
            $product->save();

            return redirect()->route('products');
        }
    }

    public function storeProduct(Request $request,)
    {
        dd($request);
        $product = new Product;
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|integer',
            'file' => 'required',
            'category' => 'required'
        ]);

        if (!$validator->passes()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $image = $request->file;
            $imageExtension = time() . '-' . $request->file->getClientOriginalName();
            
            $newImageName = time() . '-' . $request->title . '.' . $imageExtension;
            $image->move(public_path('storage/photos'), $newImageName);

            $data = [
                    'title' => $request->title,
                    'description' => $request->description,
                    'price' => $request->price,
                    'imageSource' => $newImageName,
                    'category' => $request->category
                ];
        }

        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'price' => 'required|integer',
                'file' => 'required|image',
                'category' => 'required'
            ]);

            if (!$validator->passes()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            } else {
                $image = $request->file('file');
                $imageExtension = $image->getClientOriginalExtension();
                $newImageName = time() . '-' . $request->title . '.' . $imageExtension;
                $image->move(public_path('storage/photos'), $newImageName);

                $data = [
                    'title' => $request->title,
                    'description' => $request->description,
                    'price' => $request->price,
                    'imageSource' => $newImageName,
                    'category' => $request->category
                ];

                $product->fill($data);
                $product->save();

                return response()->json(['message' => $data]);
            }
        } else {
            $newImageName = time() . '-' . $request->title . '.' . $request->image->extension();
            $request->image->move(public_path('storage/photos'), $newImageName);
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'category' => $request->category,
                'imageSource' => $newImageName
            ];
            $product->fill($data);
            $product->save();

            return redirect()->back();
        }
    }

    public function editProductView($id)
    {
        $product = Product::findOrFail($id);
        return response()->json(['products' => $product]);
    }

    public function addProductView(Request $request)
    {
        return $request->ajax() ? response()->json(['destination' => 'addProduct']) : view('product', ['destination' => 'addProduct']);
    }

    public function detailsProduct(Request $request, $id)
    {

        $product = Product::findOrFail($id);
        $category = $product->category;
        $sameCategoryproducts = Product::select('products.*')
            ->where('category', $category)
            ->where('id', '!=', $id)
            ->selectRaw('(SELECT SUM(quantity) FROM order_product WHERE order_product.product_id=products.id) as total_quantity')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        $data = [
            'product' => $product,
            'sameCategoryproducts' => $sameCategoryproducts
        ];

        return $request->ajax() ? response()->json($data) : view('product', ['product' => $product, 'destination' => 'editProduct']);
    }

    public function translationWords()
    {
        $translate = [
            'action' => 'Action',
            'actionViewOrder' => 'View Order',
            'actionViewProduct' => 'Details Product',
            'add' => 'Add',
            'addProduct' => 'Add product',
            'category' => 'Category',
            'cart' => 'Go to cart',
            'cartPage' => 'Cart Page',
            'checkout' => 'Checkout',
            'checkoutInformation' => 'Checkout Information',
            'comments' => 'Comments',
            'contactDetails' => 'Contact Details',
            'customerDetails' => 'Customer Details',
            'date' => 'Date',
            'delete' => 'Delete',
            'description' => 'Description',
            'edit' => 'Edit',
            'email' => 'Email',
            'emailError' => 'The email was not sent successfully!',
            'emailSucces' => 'The email was sent successfully!',
            'emptyCart' => 'The cart is empty!',
            'error' => 'An error has occurred. Please try again.',
            'id' => 'Id',
            'image' => 'Image',
            'insufficientStock' => 'The stock is insufficient.',
            'index' => 'Index Page',
            'invalid' => 'Invalid credentials!',
            'login' => 'Login',
            'logout' => 'Logout',
            'name' => 'Name',
            'nameShop' => 'Shop Online',
            'notOrders' => 'No orders found!',
            'notProductsInDB' => 'Not products in database',
            'order' => 'Order',
            'ordersPage' => 'Orders',
            'password' => 'Password',
            'price' => 'Price',
            'productsPage' => 'Products Page',
            'productPage' => 'Product',
            'productsRecommended' => 'Products Recommended',
            'purchasedProducts' => 'Purchased Products',
            'remove' => 'Remove',
            'setQuantity' => 'Set quantity',
            'save' => 'save',
            'seeOrder' => 'See the order',
            'submitOrder' => 'Thank you so much for your order! Below are the products you ordered!',
            'title' => 'Title',
            'totalPrice' => 'Total Price',
            'update' => 'Update',
            'userName' => 'Username',
            'yourQuantity' => 'Quantity'
        ];

        return response()->json($translate);
    }
}
