<?php

namespace App\Http\Controllers;

use App\Models\menu;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoremenuRequest;
use App\Http\Requests\UpdatemenuRequest;
use App\Models\category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Menu::with('category:id,name')->get();
        $category = category::all();
        return response()->json(["menu" => $data, "category" => $category]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoremenuRequest $request)
    {
        $request['category_id'] = (int)$request['category_id'];
        $request['price'] = (int)$request['price'];

        // return response()->json($request);

        $validatedData = $request->validate([
            'category_id' => 'required|integer|between:1,4',
            'name' => 'required|max:255',
            'description' => 'required',
            'image' => 'required|image|max:1024|mimes:jpeg,png,jpg,gif',
            'price' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('image')) {
            $fileName = $this->randomString();
            $extension = $request->file('image')->extension();
            $request->file('image')->storeAs('images', $fileName . "." . $extension);
            $validatedData['image'] = $fileName . "." . $extension;
        }

        $post = Menu::create($validatedData);

        return response()->json($post, 201);
    }

    function randomString($n = 40)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    /**
     * Display the specified resource.
     */
    public function show(menu $menu)
    {
        $menu->load('category');
        return response()->json($menu);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatemenuRequest $request, menu $menu)
    {
        // return response()->json($request);

        $request['category_id'] = (int)$request['category_id'];
        $request['price'] = (int)$request['price'];

        $validatedData = $request->validate([
            'category_id' => 'required|integer|between:1,4',
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('image')) {
            Storage::delete('images/' . $request['oldImage']);

            $fileName = $this->randomString();
            $extension = $request->file('image')->extension();
            $request->file('image')->storeAs('images', $fileName . "." . $extension);
            $validatedData['image'] = $fileName . "." . $extension;
        }

        $menu->update($validatedData);

        $menu = Menu::find($menu->id);

        return response()->json($menu, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(menu $menu)
    {
        Menu::destroy($menu->id);
        if ($menu->image) {
            Storage::delete($menu->image);
        }
        return response()->json(['message' => "Success"], 200);
    }

    public function updateMenu(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $validatedData = $request->validate([
            'category_id' => 'required|integer|between:1,4',
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|integer|min:1',
            'image' => 'image|max:1024|mimes:jpeg,png,jpg,gif',
        ]);

        if ($request->hasFile('image')) {
            Storage::delete('images/' . $menu->image);

            $fileName = $this->randomString();
            $extension = $request->file('image')->extension();
            $request->file('image')->storeAs('images', $fileName . "." . $extension);
            $validatedData['image'] = $fileName . "." . $extension;
        }

        $menu->update($validatedData);

        return response()->json($menu, 200);
    }
}
