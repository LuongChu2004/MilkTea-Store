<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        // Require login later, assuming Auth is handled via middleware
        $cart = [];
        $isBuyNow = false;
        
        if ($request->has(['id', 'num', 'price'])) {
            $isBuyNow = true;
            $product = Product::find($request->id);
            if ($product) {
                $cart = [
                    [
                        'id' => $product->id,
                        'title' => $product->title,
                        'thumbnail' => $product->thumbnail,
                        'num' => $request->num,
                        'size' => $request->size ?? 'No Size',
                        'price' => $request->price,
                        'sugar_level' => $request->sugar_level ?? '',
                        'ice_level' => $request->ice_level ?? '',
                    ]
                ];
            }
        } else {
            $cart = session()->get('cart', []);
        }

        if (empty($cart)) {
            return redirect('/cart')->with('error', 'Giỏ hàng trống');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['num'] * $item['price'];
        }

        return view('checkout', compact('cart', 'total', 'isBuyNow'));
    }

    public function process(Request $request)
    {
        $userId = Auth::id() ?? 1; // Default to 1 if not logged in for now

        $order = Order::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'note' => $request->note,
            'user_id' => $userId,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_method == 'COD' ? 'confirmed' : 'pending',
            'status' => 'Chờ xử lý'
        ]);

        $cart = $request->is_buy_now ? json_decode($request->cart_data, true) : session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $sizeInfo = $item['size'] . " - Đường: " . ($item['sugar_level'] ?? '') . " - Đá: " . ($item['ice_level'] ?? '');
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'size' => $sizeInfo,
                'num' => $item['num'],
                'price' => $item['price'],
                'user_id' => $userId,
                'status' => 'Chờ xử lý'
            ]);
            $total += $item['num'] * $item['price'];
        }

        if (!$request->is_buy_now) {
            session()->forget('cart');
        }

        if ($request->payment_method == 'COD') {
            return redirect('/history')->with('success', 'Đặt hàng thành công!');
        } else {
            // VNPay logic
            $vnp_TmnCode = "KXY0SQZ4"; //Mã định danh merchant kết nối (Terminal Id)
            $vnp_HashSecret = "CKU6HA32FLF6F20OUW8CLWZ2R6USRIW5"; //Secret key
            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_Returnurl = url('/checkout/vnpay_return');
            
            $vnp_TxnRef = $order->id; //Mã giao dịch thanh toán tham chiếu của merchant
            $vnp_Amount = $total; // Số tiền thanh toán
            $vnp_Locale = 'vn'; //Ngôn ngữ chuyển hướng thanh toán
            $vnp_BankCode = 'NCB'; //Mã phương thức thanh toán
            $vnp_IpAddr = $request->ip(); //IP Khách hàng thanh toán

            $startTime = date("YmdHis");
            $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));

            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount* 100,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => "Thanh toan GD:" . $vnp_TxnRef,
                "vnp_OrderType" => "other",
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
                "vnp_ExpireDate"=>$expire
            );

            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }

            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }
            return redirect($vnp_Url);
        }
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = "CKU6HA32FLF6F20OUW8CLWZ2R6USRIW5";
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        
        $orderId = $inputData['vnp_TxnRef'];
        $order = Order::find($orderId);

        if ($secureHash == $vnp_SecureHash) {
            if ($inputData['vnp_ResponseCode'] == '00') {
                if ($order) {
                    $order->update(['payment_status' => 'completed']);
                }
                return redirect('/history')->with('success', 'Thanh toán VNPAY thành công!');
            } else {
                if ($order) {
                    $order->update(['payment_status' => 'failed', 'status' => 'Đã hủy']);
                }
                return redirect('/history')->with('error', 'Thanh toán bị hủy hoặc có lỗi xảy ra.');
            }
        } else {
            return redirect('/history')->with('error', 'Chữ ký không hợp lệ!');
        }
    }
}
