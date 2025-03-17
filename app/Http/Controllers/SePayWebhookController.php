<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SePayWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Lấy dữ liệu từ webhook
        $data = $request->json()->all();
        
        // Kiểm tra dữ liệu có hợp lệ không
        if (empty($data)) {
            return response()->json(['success' => false, 'message' => 'No data received']);
        }

        // Khởi tạo các biến
        $gateway = $data['gateway'] ?? null;
        $transactionDate = $data['transactionDate'] ?? null;
        $accountNumber = $data['accountNumber'] ?? null;
        $subAccount = $data['subAccount'] ?? null;
        $transferType = $data['transferType'] ?? null;
        $transferAmount = $data['transferAmount'] ?? 0;
        $accumulated = $data['accumulated'] ?? null;
        $code = $data['code'] ?? null;
        $transactionContent = $data['content'] ?? null;
        $referenceNumber = $data['referenceCode'] ?? null;
        $body = $data['description'] ?? null;

        $amountIn = $transferType === 'in' ? $transferAmount : 0;

        // Lưu giao dịch vào CSDL
        DB::table('tb_transactions')->insert([
            'gateway' => $gateway,
            'transaction_date' => $transactionDate,
            'account_number' => $accountNumber,
            'sub_account' => $subAccount,
            'amount_in' => $amountIn,
            'amount_out' => $transferType === 'out' ? $transferAmount : 0,
            'accumulated' => $accumulated,
            'code' => $code,
            'transaction_content' => $transactionContent,
            'reference_number' => $referenceNumber,
            'body' => $body
        ]);

        // Tách mã đơn hàng từ nội dung thanh toán
        preg_match('/DH(\d+)/', $transactionContent, $matches);
        $payOrderId = $matches[1] ?? null;

        if (!is_numeric($payOrderId)) {
            return response()->json(['success' => false, 'message' => 'Order not found.']);
        }

        // Tìm và cập nhật đơn hàng
        $order = DB::table('orders')
            ->where('id', $payOrderId)
            ->where('total', $amountIn)
            ->where('payment_status', 'Unpaid')
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found or already paid.']);
        }

        DB::table('orders')
            ->where('id', $payOrderId)
            ->update(['payment_status' => 'Paid']);

        return response()->json(['success' => true]);
    }
}
