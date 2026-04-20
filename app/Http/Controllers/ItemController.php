<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::with('category')->orderBy('created_at', 'desc')->get();
        return view('admin.item.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.item.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'required|boolean',
            ],
            [
                'name.required' => 'The item name is required.',
                'description.string' => 'The description must be a string.',
                'price.required' => 'The price is required.',
                'category_id.required' => 'The category is required.',
                'img.image' => 'The image must be an image file.',
                'img.size' => 'The image size must not exceed 2MB.',
                'is_active.required' => 'The active status is required.',
            ]
        );

        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img_item_upload'), $imageName);

            $validatedData['img'] = $imageName;
        }

        Item::create($validatedData);

        return redirect()->route('items.index')->with('success', 'Item Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Item::findOrFail($id);
        $categories = Category::all();

        return view('admin.item.edit', compact('categories', 'item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'img' => '|sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'required|boolean',
        ]);

        // Handle image upload if provided
        if ($request->hasFile('img')) {
            // 1. Cek dan hapus gambar lama jika ada
            if ($item->img && file_exists(public_path('img_item_upload/' . $item->img))) {
                unlink(public_path('img_item_upload/' . $item->img));
            }

            // 2. Upload gambar baru
            $image = $request->file('img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img_item_upload'), $imageName);
            $validatedData['img'] = $imageName;
        }

        // Find the item and update it
        $item->update($validatedData);

        // Redirect to the items index with a success message
        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item Deleted successfully.');
    }
}
