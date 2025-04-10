<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SePayWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Lấy dữ liệu từ webhook
        $data = $request->json()->all();
        Log::info(json_encode($data));
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
        preg_match('/DHORD([A-Z0-9]+)/i', $transactionContent, $matches);
        $payOrderId = $matches[1] ?? null;

        if (!$payOrderId) {
            Log::info('Order ID not found in transaction content');
            return response()->json(['success' => false, 'message' => 'Order ID not found in transaction content']);
        }

        // Format order ID as "ORD-WPYWWOWXTC"
        $formattedOrderId = "ORD-" . $payOrderId;
        Log::info('Pay Order ID: ' . $formattedOrderId);

        // Tìm và cập nhật đơn hàng
        $order = DB::table('orders')
            ->where('order_number', $formattedOrderId)
            ->where('total_amount', $amountIn)
            ->where('payment_status', 'Unpaid')
            ->first();

        if (!$order) {
            Log::info('Order not found or already paid: ' . $formattedOrderId);
            return response()->json(['success' => false, 'message' => 'Order not found or already paid.']);
        }

        DB::table('orders')
            ->where('order_number', $formattedOrderId)
            ->update(['payment_status' => 'Paid']);

        Log::info('Order updated successfully: ' . $formattedOrderId);
        return response()->json(['success' => true]);
    }
}
