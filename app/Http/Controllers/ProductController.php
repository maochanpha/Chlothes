<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function product(){
        $data=Product::with(['user:id,name', 'category:id,cate_name'])->get();
        return view('admin.product', compact('data'));
    }
    public function addProduct(){
        $data=Category::all();
        return view('admin.addProduct', compact('data'));
    }
    public function editProduct(){
        return view('admin.editProduct');
    }
    public function createProduct(Request $request) {
        try {
            $data= $request->validate([
                'pro_name'=> 'required|string|max:255',
                'price'=> 'required|numeric',
                'stock'=> 'required|integer',
                'cate_id'=>'required|exists:categories,id',
                'image' => 'required|image',
            ]);

            $data['user_id'] = Auth::id();

            if($request->hasFile('image')){
                $file=$request->file('image');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move('product', $fileName);
                $data['image'] = url('product/' . $fileName);
            }

            Product::create($data);

            return redirect('/admin/product/add');
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function updateProduct(Request $req,$id){
        $pro=Product::findOrFail($id);
        $data =$req->validate([
            'pro_name'=> 'required|string|max:255',
            'price'=> 'required|numeric',
            'stock'=> 'required|integer',
            'cate_id'=>'required|exists:categories,id',
        ]);
        $data['user_id'] = Auth::id();
        if($req->hasFile('image')){
            $file=$req->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move('product', $fileName);
            $data['image'] = url('product/' . $fileName);
        }else{
            $data['image']=$pro['image'];
        }
        $pro->update($data);
        return redirect('/admin/product');
    }

    public function deleteProduct($id){
        $data=Product::findOrFail($id);
        $data->delete();
        return redirect('/admin/product');
    }
}
