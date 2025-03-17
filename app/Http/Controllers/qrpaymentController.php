<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use DB;

class qrpaymentController extends Controller
{
    public function payment(Request $request)
    {
        // Lấy giỏ hàng của người dùng
        $cart = Cart::where('user_id', auth()->user()->id)->where('order_id', null)->get()->toArray();
        
        $data = [];

        // Chuẩn bị dữ liệu cho thanh toán QR
        $data['items'] = array_map(function ($item) use ($cart) {
            $name = Product::where('id', $item['product_id'])->pluck('title')->first();
            return [
                'name' => $name,
                'price' => $item['price'],
                'desc' => 'Thank you for using QRPay',
                'qty' => $item['quantity'],
            ];
        }, $cart);

        // Tính tổng số tiền
        $totalAmount = 0;
        foreach ($data['items'] as $item) {
            $totalAmount += $item['price'] * $item['qty'];
        }
        
        // Gán tổng số tiền vào biến data
        $data['total_amount'] = $totalAmount;

        // Tạo mã đơn hàng
        $data['invoice_id'] = 'ORD-' . strtoupper(uniqid());
        $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";

        // Giả sử bạn có một API tạo QR code thanh toán
        $qrCodeLink = $this->generateQRCode($data);

        // Lưu mã đơn hàng vào session
        session()->put('order_id', $data['invoice_id']);

        // Trả về link QR Code
        return view('frontend.pages.qrpayment', compact('qrCodeLink'));
    }

    /**
     * Generate QR code payment link (this function is an example)
     */
    private function generateQRCode($data)
    {
        // Gọi API bên ngoài để tạo QR Code, đây chỉ là một ví dụ giả lập
        $paymentLink = "https://qr.sepay.vn/img?bank=ACBBank&acc=18721251&template=compact&amount=" . intval($data['total_amount']) . "&des=DH" . $data['invoice_id'];

        
        // Bạn có thể sử dụng một thư viện như `endroid/qr-code` để tạo QR code ở đây
        // return QRCode::generate($paymentLink);

        return $paymentLink;  // trả về link thanh toán QR
    }

    public function cancel()
    {
        // Trường hợp thanh toán bị hủy
        request()->session()->flash('error', 'Your payment is canceled. Please try again!');
        return redirect()->route('home');
    }

    public function success(Request $request)
    {
        // Lấy mã đơn hàng từ request
        $orderId = session('order_id');

        // Giả sử đây là API trả về trạng thái thanh toán QR
        $paymentStatus = $this->checkQRPaymentStatus($orderId);

        if ($paymentStatus === 'success') {
            // Cập nhật trạng thái đơn hàng
            Cart::where('user_id', auth()->user()->id)
                ->where('order_id', $orderId)
                ->update(['order_status' => 'paid']);

            session()->forget('cart');
            request()->session()->flash('success', 'You have successfully paid through QRPay! Thank you.');
            return redirect()->route('home');
        } else {
            request()->session()->flash('error', 'Something went wrong. Please try again.');
            return redirect()->route('qrpayment.cancel');
        }
    }

    /**
     * Check payment status from QRPay API (this is just an example function)
     */
    private function checkQRPaymentStatus($orderId)
    {
        // Gọi API để kiểm tra trạng thái thanh toán
        // Giả sử API trả về trạng thái thanh toán thành công hoặc thất bại
        // return 'success' | 'failed'

        return 'success';  // Giả lập thanh toán thành công
    }
}
