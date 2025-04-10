<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use DB;
use Illuminate\Support\Facades\DB as FacadesDB;

class qrpaymentController extends Controller
{
    public function payment(Request $request)
    {
        $userId = auth()->user()->id;
        $cartItems = Cart::where('user_id', $userId)->whereNull('order_id')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Tính tổng
        $subTotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $quantity = $cartItems->sum('quantity');
        $totalAmount = $subTotal; // Nếu chưa có giảm giá, phí vận chuyển

        $invoiceId = 'ORD-' . strtoupper(uniqid());

        // Lấy thông tin người dùng (giả sử từ auth user)
        $user = auth()->user();

        // Tạo đơn hàng
        FacadesDB::table('orders')->insert([
            'order_number'     => $invoiceId,
            'user_id'          => $userId,
            'sub_total'        => $subTotal,
            'total_amount'     => $totalAmount,
            'quantity'         => $quantity,
            'payment_method'   => 'QRPay',
            'payment_status'   => 'Unpaid',
            'status'           => 'pending', // hoặc default là `processing`
            'fullname'         => $user->name ?? 'Khách hàng', // nếu không có name, sửa lại theo field bạn có
            'email'            => $user->email,
            'address'          => $user->address ?? 'Chưa có địa chỉ', // bạn sửa theo nhu cầu
            'phone'            => $user->phone ?? '',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        // Gán order_id cho các item trong cart
        Cart::where('user_id', $userId)->whereNull('order_id')->update([
            'order_id' => $invoiceId
        ]);

        // Tạo link QR code
        $qrCodeLink = $this->generateQRCode([
            'total_amount' => $totalAmount,
            'invoice_id' => $invoiceId
        ]);

        session()->put('order_id', $invoiceId);

        return view('frontend.pages.qrpayment', [
            'qrCodeLink' => $qrCodeLink,
            'order_id' => $invoiceId,
        ]);
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
        $orderId = session('order_id');

        $paymentStatus = $this->checkQRPaymentStatus($orderId);

        if ($paymentStatus === 'success') {
            // Cập nhật trạng thái đơn hàng
            FacadesDB::table('orders')->where('order_number', $orderId)->update([
                'payment_status' => 'Paid',
                'status' => 'confirmed',
                'updated_at' => now(),
            ]);

            // Cập nhật giỏ hàng đã thanh toán
            Cart::where('user_id', auth()->user()->id)
                ->where('order_id', $orderId)
                ->update(['order_status' => 'paid']);

            session()->forget('cart');
            request()->session()->flash('success', 'Bạn đã thanh toán thành công qua QR!');
            return redirect()->route('home');
        } else {
            request()->session()->flash('error', 'Thanh toán thất bại. Vui lòng thử lại.');
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
    public function checkPaymentStatus(Request $request)
    {
        $id = $request->input('id');

        $order = FacadesDB::table('orders')->where('order_number', $id)->first();

        if (!$order) {
            return response()->json(['payment_status' => 'NotFound']);
        }

        return response()->json([
            'payment_status' => $order->payment_status,
        ]);
    }
}
