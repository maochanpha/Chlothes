<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function category(){
        $cate=Category::with('users:id,name')->get();
        return view('admin.category', compact('cate'));
    }
    public function addCategory(){
        return view('admin.addCategory');
    }
    public function editCategory($id){
        $data=Category::FindOrFail($id);
        return view('admin.editCategory', compact('data'));
    }
    public function deleteCategory($id){
        $data=Category::FindOrFail($id);
        $data->delete();
        return redirect('/admin/category');
    }
    public function updateCategory(Request $req){
        try {
        $cate=Category::FindOrFail($req->id);
        $data= $req->validate([
            'cate_name'=>'required', 
        ]);
        $data['user_id']=Auth::user()->id;
        if($req->hasFile('image')){
            $file= $req->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move('category', $filename);
            $data['image'] = url('category/' . $filename);
        }else{
            $data['image']=$cate['image'];
        }
        $cate->update($data);
        return redirect('/admin/category');
       }catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function createCategory(Request $req){
       try {
        $data= $req->validate([
            'cate_name'=>'required', 
        ]);
        $data['user_id']=Auth::user()->id;
        if($req->hasFile('image')){
            $file= $req->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move('category', $filename);
            $data['image'] = url('category/' . $filename);
        }
        Category::create($data);
        return redirect('/admin/category/add');
       }catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
