<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Productimg;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class Products extends Controller
{

 //edit product

    public function editProducts($id)
    {
        $products = Product::findOrFail($id);
        $totalProducts = Product::count();  
        if (!$products) {
            return redirect()->back()->withErrors('Product not found.');
        }
        return view("admin.AeditProducts",compact('products','totalProducts'));
        
    }

    /////update product
    public function updateProducts(Request $request,$id)
    {
        
        $request->validate([
            'productName' => 'max:50',
            'category' => 'max:50',
            'productPrice' => 'max:50',
            'productStock' => 'max:50',
            'productWeight' => 'max:50',
            'description' => 'max:500',
            'productStatus' => 'max:10', 
        ]);
        $status = $request->input('productStatus');
        if($status == "active"){
            $status = 1;
        }else{
            $status = 0;
        }
        $product = Product::findOrFail($id);
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

    public function paymentDetails(Request $request)
    {
        $rawProducts = $request->products;
        $selectedProducts = [];
        $decoded = json_decode($rawProducts, true);

        if (
            json_last_error() === JSON_ERROR_NONE &&
            is_array($decoded) &&
            isset($decoded[0]['id'])
        ) {
            // JSON cart format
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

        $buyproduct = product::with('images')
            ->whereIn('id', $productIds)
            ->get()
            ->map(function ($product) use ($selectedProducts) {
                $match = collect($selectedProducts)->firstWhere('id', $product->id);
                $product->qty = $match['qty'] ?? 1;
                return $product;
            });

        return view('users/Ucheckout', compact('buyproduct'));
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
        $products = Product::with('images')->where('status', '1')->paginate(20);
        return view('users.Uproducts',compact('products'));
    }

    /// display products for admin panel
    public function index()
    {
        $prod = Product::with('images')->paginate(20);
        $totalProducts = Product::count();
        $lowStock=Product::where('stock','<=',20)->count(); 
        $activeProducts = Product::where('status','1')->count();
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

    ////delete product

    public function deleteProducts($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return back()->withsuccess("Product deleted successfully!");
    }
}









//removed part of admin edit product 
   


//removed part of admin add product

    // public function addProducts(Request $Request)
    // {
         
    //     $Request->validate([
    //         'productName' => 'required|max:50',
    //         'category' => 'required',
    //         'price' => 'required|numeric',
    //         'stock' => 'required|integer',
    //         'description' => 'required|max:200',
    //         'productImages' => 'nullable',
    //         'productImages.*' => 'image',
    //         'status' => 'required',
    //         'type' => 'nullable|array',
    //         'type.*' => 'string',
    //         'weight' => 'required|numeric',
    //     ]);

    //     $save = Product::create([
    //         'product_name' => $Request->productName,
    //         'category' => $Request->category,
    //         'price' => $Request->price,
    //         'stock' => $Request->stock,
    //         'description' => $Request->description,
    //         'status' => $Request->status,
    //         'type' => $Request->type,  
    //         'weight' => $Request->weight,
    //         'seller_id' => Auth::id(),
    //     ]);
        

    //     if ($Request->hasFile('productImages')) {                          // for multiple image storage
    //         foreach ($Request->file('productImages') as $image) {
    //             $imagePath = $image->store('images', 'public');

    //             Productimg::create([
    //                 'product_id' => $save->id,
    //                 'image' => $imagePath,
    //             ]);
    //         }
    //     }
    //     return redirect()->back()->with('success', 'Product added successfully!');
    // }