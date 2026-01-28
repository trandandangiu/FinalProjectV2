<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Termwind\Components\Ol;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;

use function Flasher\Toastr\Prime\toastr;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $addresses = ShippingAddress::where('user_id', $user->id)->get();
        $defaultAddress = $addresses->where('default', 1)->first();
        if (is_null($addresses) || is_null($defaultAddress)) {
            toastr()->error('VUi lòng thêm địa chỉ giỏ hàng');
            return redirect()->route('account');
        }
        $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();
        $totalPrice = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);


        return view('clients.pages.checkout', compact('addresses', 'defaultAddress', 'cartItems', 'totalPrice'));
    }

    public function getAddress(Request $request)
    {
        $address = ShippingAddress::where('id', $request->address_id)
            ->where('user_id', Auth::id())->first();
        if (!$address) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy địa chỉ!']);
        }
        return response()->json([
            'success' => true,
            'data' => $address
        ]);
    }

    public function placeOrder(Request $request)
    {
        $user = Auth::user();
        $cartItems = CartItem::where('user_id', $user->id)->get();


        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Giỏ hàng trống');
        }
        DB::begintransaction();

        try {
            //tạo order
            $order = new Order();
            $order->user_id = $user->id;
            $order->shipping_address_id = $request->address_id;
            $order->total_price = $cartItems->sum(fn($item) => $item->quantity * $item->product->price) + 25000;
            $order->status = 'pending'; //default is pending
            $order->save();

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price


                ]);
            }


            //create payment 
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'amount' => $order->total_price,
                'status' => 'pending',
                'paid_at' => null,
            ]);
            //xóa giỏ hàng khi đặt hàng thành công 
            CartItem::where('user_id', $user->id)->delete();
            DB::commit();
            toastr()->success('Đặt hàng thành công');
            return redirect()->route('account');
        } catch (\Exception $e) {
            toastr()->error('Có lỗi xảy ra, vui lòng thử lại');
            return redirect()->route('checkout.index');
        }
    }

   
}
