<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $tableNumber = $request->query('meja');

        if ($tableNumber) {
            Session::put('tableNumber', $tableNumber);
        }

        $items = Item::where('is_active', 1)->orderBy('name', 'asc')->get();

        return view('customer.menu', compact('items', 'tableNumber'));
    }

    public function cart()
    {
        $cart = Session::get('cart');
        return view('customer.cart', compact('cart'));
    }

    public function addToCart(Request $request)
    {
        $menuId = $request->input('id');
        $menu = Item::find($menuId);

        if (!$menu) {
            return response()->json([
                'status' => 'error',
                'message' => 'Menu tidak ditemukan'
            ]);
        }

        $cart = Session::get('cart');

        if (isset($cart[$menuId])) {
            $cart[$menuId]['qty'] += 1;
        } else {
            $cart[$menuId] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => $menu->price,
                'image' => $menu->img,
                'qty' => 1
            ];
        }

        // Jangan lupa simpan kembali ke session setelah update
        Session::put('cart', $cart);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil Ditambahkan ke keranjang',
            'cart' => $cart
        ]);
    }

    public function updateCart(Request $request)
    {
        $itemId = $request->input('id');
        $newQty = $request->input('qty');

        if ($newQty <= 0) {
            return response()->json([
                'success' => false,
            ]);
        }

        $cart = Session::get('cart');

        if (isset($cart[$itemId])) {
            $cart[$itemId]['qty'] = $newQty;
            Session::put('cart', $cart);
            Session::flash('success', 'Jumlah item berhasil diperbarui');

            return response()->json([
                'success' => true
            ]);
        }
        return response()->json([
            'success' => false
        ]);
    }

    public function removeItem(Request $request)
    {
        $itemId = $request->input('id');
        $cart = session()->get('cart');

        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);

            Session::put('cart', $cart);
            Session::flash('success', 'Item berhasil dihapus');
        }
        return response()->json(['success' => true]);
    }

    public function clearCart(Request $request)
    {
        // kita lupakan si keranjangnya
        $request->session()->forget('cart');
        return redirect()->route('cart')->with('success', 'Keranjang Berhasil Dikosongkan');
    }

    // Checkout 

    public function checkout()
    {
        $cart = Session::get('cart');

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang Masih Kosong');
        }

        $tableNumber = Session::get('tableNumber');

        return view('customer.checkout', compact('cart', 'tableNumber'));

    }

    public function storeOrder(Request $request)
    {
        $cart = Session::get('cart');
        $tableNumber = Session::get('tableNumber');

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang Masih Kosong');
        }

        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            // Jika permintaan adalah AJAX/Fetch, kirim JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 422);
            }
            // Jika request biasa (tunai), tetap redirect
            return redirect()->route('checkout')->withErrors($validator)->withInput();
        }

        // 1. Hitung total keseluruhan dari keranjang
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $totalAmount = 0;

        foreach ($cart as $item) {
            // Menghitung total harga keseluruhan
            $totalAmount += $item['qty'] * $item['price'];
            $itemDetails = [];

            // Menyusun detail item (biasanya untuk Payment Gateway seperti Midtrans)
            $itemDetails[] = [
                'id' => $item['id'],
                // Harga satuan ditambah pajak 10% (sesuai logika di gambar)
                'price' => (int) ($item['price'] + ($item['price'] * 0.1)),
                'quantity' => $item['qty'],
                'name' => substr($item['name'], 0, 50), // Batasi nama maksimal 50 karakter
            ];
        }

        $user = User::firstOrCreate([
            'fullname' => $request->input('fullname'),
            'phone' => $request->input('phone'),
            'role_id' => 4
        ]);

        $order = Order::create([
            'order_code' => 'ORD-' . $tableNumber . time(),
            'user_id' => $user->id,
            'subtotal' => $totalAmount,
            'tax' => 0.1 * $totalAmount,
            'grandtotal' => $totalAmount + (0.1 * $totalAmount),
            'status' => 'pending',
            'table_number' => $tableNumber,
            'payment_method' => $request->payment_method,
            'note' => $request->note,
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,        // ID dari hasil Order::create sebelumnya
                'item_id' => $item['id'],       // ID produk/menu
                'quantity' => $item['qty'],      // Jumlah yang dipesan
                'price' => $item['price'] * $item['qty'], // Total harga sebelum pajak
                'tax' => 0.1 * $item['price'] * $item['qty'], // Pajak 10% dari total item
                'total_price' => ($item['price'] * $item['qty']) + (0.1 * $item['price'] * $item['qty']), // Harga akhir (Item + Pajak)
            ]);
        }

        // 4. Hapus data session setelah pesanan berhasil disimpan
        Session::forget('cart');

        if ($request->payment_method == 'tunai') {
            return response()->json([
                'status' => 'success',
                'payment_method' => 'tunai',
                'order_code' => $order->order_code,
                'redirect_url' => route('checkout.success', ['orderId' => $order->order_code])
            ]);
        } else {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$clientKey = config('midtrans.client_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_code,
                    'gross_amount' => (int) $order->grandtotal,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $user->fullname ?? 'Guest',
                    'phone' => $user->phone,
                ],
            ];

            try {
                // Meminta Snap Token dari server Midtrans
                $snapToken = \Midtrans\Snap::getSnapToken($params);

                return response()->json([
                    'status' => 'success',
                    'snap_token' => $snapToken,
                    'order_code' => $order->order_code,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal membuat pesanan. Silakan coba lagi.'
                ]);
            }

        }

    }

    public function checkoutSuccess($orderId)
    {
        $order = Order::where('order_code', $orderId)->first();

        if (!$order) {
            return redirect()->route('menu')->with('error', 'Pesanan tidak ditemukan');
        }

        $orderItems = OrderItem::where('order_id', $order->id)->get();

        if ($order->payment_method == 'qris') {
            $order->status = 'settlement';
            $order->save();
        }

        return view('customer.success', compact('order', 'orderItems'));
    }
}
