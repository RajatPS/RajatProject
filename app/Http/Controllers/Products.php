<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Productimg;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;


class Products extends Controller
{

 //edit product

    public function editProducts(Request $request)
    {
        $id = $request->input('productId');
        $products = Product::findOrFail($id);
        
        // Authorization: Check if the product belongs to the current seller
        if ($products->seller_id != Auth::id()) {
            return redirect()->back()->withErrors('You do not have permission to edit this product.');
        }
        
        $totalProducts = Product::count();  
        if (!$products) {
            return redirect()->back()->withErrors('Product not found.');
        }
        return view("seller.sellereditProducts",compact('products','totalProducts'));
        
    }

    /////update product
    public function updateProducts(Request $request){
        $request->validate([
            'productName' => 'max:50',
            'category' => 'max:50',
            'productPrice' => 'max:50',
            'productStock' => 'max:50',
            'productWeight' => 'max:50',
            'description' => 'max:1000',
            'productStatus' => 'max:10',
        ]);
        
        $productId = $request->input('productId');
        $product = Product::findOrFail($productId);
        
        // Authorization: Check if the product belongs to the current seller
        if ($product->seller_id != Auth::id()) {
            return redirect()->back()->withErrors('You do not have permission to update this product.');
        }
        
        // Fixed: Convert status properly to boolean (1/0 for true/false)
        $status = $request->input('productStatus');
        if(strtolower($status) == "active" || $status == 1 || $status == "true"){
            $status = 1;
        } else {
            $status = 0;
        }
        
        $product->update([
            'product_name' => $request->input('productName'),
            'category' => $request->input('category'),
            'price' => $request->input('productPrice'),
            'stock' => $request->input('productStock'),
            'weight' => $request->input('productWeight'),
            'description' => $request->input('description'),
            'status' => $status,
        ]);
    
        return redirect('seller/products/')->with('success', 'Product updated successfully!');
    }

  

    ///////////////////////////////////////////////////////////

    public function productcheckout(Request $request)
    {
        $rawProducts = $request->products;
        $selectedProducts = [];
        $decoded = json_decode($rawProducts, true);

        if (
            json_last_error() === JSON_ERROR_NONE &&
            is_array($decoded) &&
            isset($decoded[0]['id'])
        ) {
            $selectedProducts = $decoded;

        } else {
            // Single ID or comma-separated IDs
            $ids = explode(',', $rawProducts);

            $selectedProducts = collect($ids)->map(function ($id) {
                return [
                    'id' => (int) $id,
                    'qty' => 1
                ];
            })->toArray();
        }

        if (empty($selectedProducts)) {
            return redirect()->back()->with('error', 'No products selected');
        }

        $productIds = collect($selectedProducts)->pluck('id')->toArray();

        $buyproduct = Product::with('images')
            ->whereIn('id', $productIds)
            ->get()
            ->map(function ($product) use ($selectedProducts) {
                $match = collect($selectedProducts)->firstWhere('id', $product->id);
                $product->qty = $match['qty'] ?? 1;
                return $product;
            });

        return view('users.Ucheckout', compact('buyproduct'));
    }



/////////////////////////////////////////////////////////////////////////////////////

    public function buyProduct(Request $request, $id)
    {
        $buyproduct = Product::with('images')->findOrFail($id);
        $image=Productimg::where('product_id',$id)->first();
        return view('users.Uproducts_details', compact('buyproduct','image'));
    }

    public function viewProducts()
    {
        $products = Product::with('images')->where('status', '1')->where('stock', '>', 25)->withoutTrashed()->paginate(15);
        $Fproducts = Product::with('images')->where('status', '1')->where('stock', '>', 25)->withoutTrashed()
        ->whereJsonContains('type', 'featured')
        ->orderBy('created_at', 'desc')
        ->take(6)
        ->get();
        $productsfornewUsers = Product::with('images')
            ->where('status', '1')
            ->withoutTrashed()
            ->where(function($query) {
                $query->whereJsonContains('type', 'featured')
                    ->orWhereJsonContains('type', 'new')
                    ->orWhereJsonContains('type', 'onSale');
            })
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
        return view('users.Uproducts',compact('products','productsfornewUsers','Fproducts'));
    }

    /// display products for admin panel
    public function index()
    {
        $prod = Product::with('images')->withoutTrashed()->paginate(20);
        $totalProducts = Product::withoutTrashed()->count();
        $lowStock=Product::withoutTrashed()->where('stock','<=',20)->count(); 
        $activeProducts = Product::withoutTrashed()->where('status','1')->count();
        return view('admin.Aproducts',compact('prod','totalProducts','activeProducts','lowStock',));
    }

    
    public function toggleStatus(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $newStatus = $request->input('status');
        $product->status = $newStatus;
        $product->save();
        return response()->json([
            'success' => true,
            'status' => $product->status,
        ]);
        
    }

    // add products
    public function addProducts(Request $request)
    {
        // dd($request->all()); // Debugging line to check incoming request data
         
        $request->validate([
            'productName' => 'required|max:50',
            'category' => 'required|max:50',
            'brand' => 'nullable|max:50',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'description' => 'required|max:1000',
            'productImages' => 'nullable',
            'productImages.*' => 'image',
            'status' => 'required',
            'type' => 'nullable|array',
            'type.*' => 'string',
            'weight' => 'required|numeric',
        ],
        [
            'description.max' => 'Description is too long.',
        ]
        );
        
        // Fixed: Convert status to boolean properly
        $status = $request->input('status');
        if(strtolower($status) == "active" || $status == 1 || $status == "true"){
            $status = 1;
        } else {
            $status = 0;
        }

        $save = Product::create([
            'product_name' => $request->productName,
            'category' => $request->category,
            'brand' => $request->brand,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'status' => $status,
            'type' => $request->type,  
            'weight' => $request->weight,
            'seller_id' => Auth::id(),
        ]);
        

        if ($request->hasFile('productImages')) {                          // for multiple image storage
            foreach ($request->file('productImages') as $image) {
                $imagePath = $image->store('images', 'public');

                Productimg::create([
                    'product_id' => $save->id,
                    'image' => $imagePath,
                ]);
            }
        }
        return redirect()->back()->with('success', 'Product added successfully!');
    }

    ////delete product

    public function deleteProducts(Request $request)
    {
        $id = $request->input('productId');
        $product = Product::findOrFail($id);
        
        // Authorization: Check if the product belongs to the current seller
        if ($product->seller_id != Auth::id()) {
            return back()->with('error', 'You do not have permission to delete this product.');
        }
        
        $product->delete();
        return back()->with('success', "Product deleted successfully!");
    }
}









//removed part of admin edit product 
   


//removed part of admin add product

