<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'product_id' => 'required|integer',
            'count' => 'required|integer',
            'note' => 'nullable|string',
        ]);

        // Tambahkan user_id ke data yang telah divalidasi
        $validatedData['user_id'] = Auth::user()->id;

        // Periksa apakah ada entri yang cocok di tabel cart
        $cartItem = Cart::where('product_id', $validatedData['product_id'])
            ->where('user_id', $validatedData['user_id'])
            ->first();

        if ($cartItem) {
            $cartItem->count = $validatedData['count'];
            $cartItem->note = $validatedData['note'];
            $cartItem->save();
            $message = "Berhasil Mengupdate keranjang!";
        } else {
            $cartItem = Cart::create($validatedData);
            $message = "Berhasil Menambahkan keranjang!";
        }

        // Kembalikan respon JSON
        return response()->json([
            'data' => $cartItem,
            'message' => $message
        ], 200);
    }

    public function removeFromCart(Cart $cart)
    {
        $userId = Auth::user()->id;

        if ($cart->user_id === $userId) {
            $cart->delete();
            return response()->json([
                'message' => 'Berhasil menghapus item dari keranjang!'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Item tidak ditemukan di keranjang atau bukan milik Anda!'
            ], 404);
        }
    }

    public function getItemFromCart()
    {
        // header("Access-Control-Allow-Origin: *");

        header("Access-Control-Allow-Methods: *");

        $userId = Auth::user()->id;
        $cart = Cart::where('user_id', $userId)->with('menu')->get();

        if ($cart->isNotEmpty()) {
            return response()->json(["cart" => $cart], 200);
        } else {
            return response()->json("no item");
        }
    }

    public function incrementItem($id)
    {
        Cart::where('id', $id)->increment('count');
        return response()->json("success");
    }
    public function decrementItem($id)
    {
        Cart::where('id', $id)->decrement('count');
        return response()->json("success");
    }
}
