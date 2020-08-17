<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    private $success_status     =   200;

    public function createProduct(Request $request) {
        $user       =   Auth::user();
        $validator  =   Validator::make($request->all(),
         [
            "title"             =>  "required|min:5",
            "category"          =>  "required",
            "description_short" =>  "required|min:10|max:50",
            "description_long"  =>  "required|min:10|max:255",
            "used"              =>  "required|",
            "location"          =>  "required|min:5",
            "town"              =>  "required|min:5",
            "price"             =>  "required",
            "image"             =>  " ",
         ]
         );

        if ($validator->fails()) {
            return response()->json(["status" => "failed", "validation_error" => $validator->errors()]);
        }

        $productDataArray   =   array (
            "author"            =>  $user->firstname,
            "slug"              =>  $this->slugGenerator($request->title),
            "title"             =>  $request->title,
            "category"          =>  $request->category,
            "description_short" =>  $request->description_short,
            "description_long"  =>  $request->description_long,
            "used"              =>  $request->used,
            "location"          =>  $request->location,
            "town"              =>  $request->town,
            "price"             =>  $request->price,
            "image"             =>  $request->image,
            "id_users"          =>  $user->id,
            "status"            =>      1,
            "published"         =>      1,
         );    

        $product           =           Product::create($productDataArray);
        if(!is_null($product)) {
            return response()->json(["data" => $product,"status" => $this->success_status, "success" => true, "message" => "Product created successfully"]);
        }
        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! failed to create product 2"]);
        }
    }
    //------------------ [ Update Product ] -----------------------

    public function updateProduct(Request $request) {
        $user       =   Auth::user();
        $validator  =   Validator::make($request->all(),
         [
            "title"             =>  "required|min:5",
            "category"          =>  "required",
            "description_short" =>  "required|min:10|max:50",
            "description_long"  =>  "required|min:10|max:255",
            "used"              =>  "required|",
            "location"          =>  "required|min:5",
            "town"              =>  "required|min:5",
            "price"             =>  "required",
            "image"             =>  " ",
         ]
        );

        if ($validator->fails()) {
            return response()->json(["status" => "failed", "validation_error" => $validator->errors()]);
        }

        $product = Product::find($request->id);

        $product->author            =  $user->id;
        $product->slug              =  $this->slugGenerator($request->title);
        $product->title             =  $request->title;
        $product->category          =  $request->category;
        $product->description_short =  $request->description_short;
        $product->description_long  =  $request->description_long;
        $product->used              =  $request->used;
        $product->location          =  $request->location;
        $product->town              =  $request->town;
        $product->price             =  $request->price;
        $product->image             =  $request->image;
        $product->id_users          =  $user->id;
        $product->status            =  1;
        $product->published         =  1;
            
        $verif = $product->save();
        if(!is_null($verif)) {
            return response()->json(["status" => $this->success_status, "success" => true, "message" => "Product created successfully", "data" => $product]);
        }
        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! failed to create product 2"]);
        }
    }

    // ----------------- [ Slug Generator ] -----------------------

    public function slugGenerator($title) {
        $slug       =       str_replace("", "-", strtolower($title));
        $slug       =       preg_replace("/[^A-Za-z0-9\-]/", "-", $slug);
        $slug       =       preg_replace("/-+$&/", "-", $slug);
        return $slug;
    }

    // ---------------- [ product listing ] --------------------
    public function productListing() {
        $products       =      Product::orderBy('created_at', 'DESC')->where("published", 1)->where("status", 1)->get();
        if(count($products) > 0) {
            return response()->json(["status" => $this->success_status, "succcess" => true, "count" => count($products), "products" => $products]);
        }
        else {
            return response()->json(["status" => "failed", "success" => false, "count" => count($products), "message" => "Whoops! no products found"]);
        }
        // if (request('q') !== null) {
        //     $products = Product::where('title','like','%'.request('q').'%')->get();
        //     return response()->json(['products'=> $products]);
        // }
        
    }
    // ------------------- [ product listing User ] --------------
    public function productUser() {
        $id =Auth::user()->id;
        $products     =    Product::where('id_users', $id)->get();
        
            return response()->json(["succcess" => true, "data" => $products]);
        
    }
    // -------------------- [ product Detail ] -------------------
    public function productDetail($slug) {
        
        $product       =       Product::where("slug", $slug)->first();
        $user = User::where('id',$product->id_users)->first();

        if(!is_null($product)) {
            return response()->json(["status" => $this->success_status, "success" => true, "data" => $product, 'user'=> $user]);
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! no product found"]);
        }
    }

    // ------------------- [ Delete product ] -------------------
    public function destroy($id) {

        $product = Product::find($id);
        $product->delete();
        return response()->json(['message'=> 'product deleted', "data"=> $id]);
    }
    // ------------------- [ Search product ] -------------------
    public function search($q){
        if ($q !== null){
        $products = Product::where('title','like','%'.$q.'%')
                            ->orwhere('category','like','%'.$q.'%')
                            ->orwhere('used','like','%'.$q.'%')
                            ->orwhere('price','like','%'.$q.'%')
                            ->get();
        return response()->json(['data'=> $products]);
        } 
        
        
    }

}
