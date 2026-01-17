<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->get();

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'attachment' => ['nullable', 'file', 'max:10240'],
            'price' => ['required', 'integer', 'min:1'],
        ]);

        // Preço já vem em centavos do JavaScript

        $disk = config('filesystems.default');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', $disk);
        }

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('attachments', $disk);
        }

        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('status', 'Produto criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'attachment' => ['nullable', 'file', 'max:10240'],
            'price' => ['required', 'integer', 'min:1'],
        ]);

        // Preço já vem em centavos do JavaScript

        $disk = config('filesystems.default');

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk($disk)->delete($product->image);
            }

            $validated['image'] = $request->file('image')->store('products', $disk);
        }

        if ($request->hasFile('attachment')) {
            if ($product->attachment) {
                Storage::disk($disk)->delete($product->attachment);
            }

            $validated['attachment'] = $request->file('attachment')->store('attachments', $disk);
        }

        $product->update($validated);

        return redirect()
            ->route('products.show', $product)
            ->with('status', 'Produto atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $disk = config('filesystems.default');

        if ($product->image) {
            Storage::disk($disk)->delete($product->image);
        }

        if ($product->attachment) {
            Storage::disk($disk)->delete($product->attachment);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('status', 'Produto excluído com sucesso.');
    }
}
