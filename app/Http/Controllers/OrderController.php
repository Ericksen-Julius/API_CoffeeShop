<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Item;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function addOrder(Request $request)
    {
        $data = new Order();

        $data->total_price = (int) $request->input('total_price');
        $data->user_id = Auth::user()->id;

        $data->save();

        $newInsertedId = $data->id;
        $items = Cart::where('user_id', Auth::user()->id)->get();

        foreach ($items as $row) {
            $item = new Item();

            $item->order_id = $newInsertedId;
            $item->product_id = $row->product_id;
            $item->count = $row->count;
            $item->note = $row->note;

            $item->save();
        }

        $transaction = new Transaction();
        $transaction->order_id = $newInsertedId;
        $transaction->save();

        // Cart::where('user_id', Auth::user()->id)->delete();

        return response()->json([
            "order" => $data,
            "transaction" => $transaction,
            "message" => "Berhasil melakukan order!!"
        ], 200);
    }
}
