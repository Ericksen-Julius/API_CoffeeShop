<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Item;
use App\Models\Menu;
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
            Menu::where('id', $row->product_id)->increment('sold', $row->count);
        }
        // Cart::where('user_id', Auth::user()->id)->delete();


        // Cart::where('user_id', Auth::user()->id)->delete();

        return response()->json([
            "order" => $data,
            "message" => "Berhasil melakukan order!!"
        ], 200);
    }

    public function updatePayment($id, $status)
    {
        if ($status == 'settlement') {
            // return $status;
            Order::where('id', $id)->update(['status' => "Paid"]);
            return response()->json([
                "message" => "Payment successful",
            ], 200);
        } else {
            Order::where('id', $id)->update(['status' => "Unpaid"]);
            return response()->json([
                "message" => "Payment failed",
            ], 200);
        }
    }

    public function getOrder()
    {

        header("Access-Control-Allow-Methods: *");

        $userId = Auth::user()->id;
        $order = Order::where('user_id', $userId)->with('item.menu')->orderBy('id', 'desc')->get();

        if ($order->isNotEmpty()) {
            $ordersTransformed = $order->map(function ($order) {
                $left_item_count = $order->item->skip(1)->sum('count');
                return [
                    'order_id' => $order->id,
                    'date' => $order->created_at->toDateString(),
                    'status' => $order->status,
                    'total_price' => $order->total_price,
                    'total_left' => $left_item_count,
                    'list_item' => $order->item->map(function ($item) {
                        return [
                            'name' => $item->menu->name,
                            'image' => $item->menu->image ?? null,
                            'price' => $item->menu->price,
                            'quantity' => $item->count,

                        ];
                    }),
                ];
            });
            return response()->json(["orders" => $ordersTransformed], 200);
        } else {
            return response()->json(["orders" => []]);
        }
    }
}
