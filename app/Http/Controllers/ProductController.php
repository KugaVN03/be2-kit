<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('products.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'max:255'],
            'price' => ['required', 'numeric'],
            'description' => ['required'],
            'photo' => ['required'],
            'categories' => ['required'],
        ], [
            'name.required' => 'Phải nhập tên',
            'name.max' => 'Tên quá dài, tối đa 255 ký tự',
            'price.required' => 'Phải nhập giá',
            'price.numeric' => 'Giá là số',
            'description.required' => 'Phải nhập mô tả',
            'photo.required' => 'Phải nhập hình',
            'categories.required' => 'Phải chọn danh mục',
        ]);
        $path = $request->photo->store('images', 'public');
        $product = new Product($validated);
        $product->photo = basename($path);
        $product->save();
        $product->categories()->attach($validated['categories']);
        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        return view('products.show', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::find($id);
        $categories = Category::all();
        return view('products.edit', ['product' => $product, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'max:255'],
            'price' => ['required', 'numeric'],
            'description' => ['required'],
            'categories' => ['required'],
        ], [
            'name.required' => 'Phải nhập tên',
            'name.max' => 'Tên quá dài, tối đa 255 ký tự',
            'price.required' => 'Phải nhập giá',
            'price.numeric' => 'Giá là số',
            'description.required' => 'Phải nhập mô tả',
            'categories.required' => 'Phải chọn danh mục',
        ]);

        $product = Product::find($id);
        if ($request->hasFile('photo')) {
            Storage::disk('public')->delete('images/' . $product->photo);
            $path = $request->photo->store('images', 'public');
            $product->photo = basename($path);
        }

        $product->update($validated);
        $product->categories()->sync($validated['categories']);
        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        $product->delete();
        return redirect()->route('products.index');
    }

    public function bin()
    {
        $products = Product::onlyTrashed()->get();
        return view('products.bin', ['products' => $products]);
    }
    public function restore(string $id)
    {
        Product::onlyTrashed()->find($id)->restore();
        return redirect()->route('products.index');
    }
    public function forceDelete(string $id)
    {
        $product = Product::onlyTrashed()->find($id);
        Storage::disk('public')->delete('images/' . $product->photo);
        $product->forceDelete();
        return redirect()->route('products.index');
    }

    public function bulkAction(Request $request)
    {   
        $action = $request->action;
        $ids = $request->ids;
        
        if ($action == "delete") {
            $products = Product::onlyTrashed()->find($ids);
            foreach ($products as $product) {
                Storage::disk('public')->delete('images/' . $product->photo);
            }
            // $products->each->forceDelete();
            Product::whereIn('id', $ids)->forceDelete();
        }
        else {
            Product::whereIn('id', $ids)->restore();
        }
        return redirect()->route('products.bin');
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q');
        $products = Product::where('name', 'like', "%$keyword%")->get();
        return view('products.search', ['products' => $products]);
    }
}
