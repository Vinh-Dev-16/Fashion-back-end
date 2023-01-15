<?php

namespace App\Http\Controllers\admin;

use App\Models\admin\Brand;
use Illuminate\Http\Request;
use App\Models\admin\Product;
use App\Models\admin\Category;
use Exception;

class productController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(6);
        $categories = Category::all();
        $count = Product::count();
        return view('admin.product.index', compact('products', 'categories', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $brands = Brand::all();
        return view('admin.product.create',compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->isMethod('Post')) {
            $rules = [
                'name' => 'required|max:255',
                'price' => 'required|integer',
                'thumbnail' => 'required',
                'discount' => 'required|integer',
                'stock' => 'required|integer',
                'desce' => 'required',
                'brand_id' => 'required',
            ];
            $messages = [
                'required' => 'Không được để trống trường này',
                'integer' => 'Trường nhập vào phải là số',
                'date' => 'Trường nhập vào phải là ngày tháng,'
            ];
            $request->validate($rules, $messages);
        }
        try {
            $input = $request->all();
            $brand = $input['brand_id'];
            $input['brand_id'] = implode(',', $brand);
            Product::create($input);
            return redirect('admin/product/index')->with('thongbao', 'Đã thêm thành công');
        } catch (Exception $e) {
            return redirect('admin/product/create')->with('loi', 'Da loi');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $products = Product::find($id);
        return view('admin.product.edit', compact('products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        try {
            $products = Product::find($id);
            $input = $request->all();
            $products->update($input);
            return redirect('admin/product/index')->with('sua', 'Da sua');
        } catch (Exception $e) {
            return redirect('admin/product/edit/' . $id)->with('loi', 'Da loi');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $products = Product::find($id);
        $products->delete();
        return redirect('admin/product/index')->with('xoa', 'Da xoa');
    }
}
