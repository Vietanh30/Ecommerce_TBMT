<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\User;
use App\Models\Product;
use PDF;
use Notification;
use Helper;
use Illuminate\Support\Str; 
use App\Notifications\StatusNotification;
use App\Exports\OrderReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductDetailsExport;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders=Order::orderBy('id','DESC')->paginate(10);
        return view('backend.order.index')->with('orders',$orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    // Kiểm tra và validate dữ liệu đầu vào
    $this->validate($request, [
        'fullname' => 'required|string',
        'address' => 'required|string',
        'phone' => 'required|string',
        'email' => 'required|string|email',
    ]);

    // Kiểm tra giỏ hàng
    if (empty(Cart::where('user_id', auth()->user()->id)->where('order_id', null)->first())) {
        request()->session()->flash('error', 'Cart is Empty!');
        return back();
    }

    // Tạo đơn hàng mới
    $order = new Order();
    $order_data = $request->all();
    $order_data['order_number'] = 'ORD-' . strtoupper(Str::random(10));
    $order_data['user_id'] = $request->user()->id;
    $order_data['sub_total'] = Helper::totalCartPrice();
    $order_data['quantity'] = Helper::cartCount();
    $order_data['total_amount'] = Helper::totalCartPrice();
    
    // Kiểm tra phương thức thanh toán và cập nhật trạng thái thanh toán
    if (request('payment_method') == 'paypal') {
        $order_data['payment_method'] = 'paypal';
        $order_data['payment_status'] = 'paid';
    } elseif (request('payment_method') == 'qrpay') {
        $order_data['payment_method'] = 'qrpay';
        $order_data['payment_status'] = 'Unpaid';
    } else {
        $order_data['payment_method'] = 'cod';
        $order_data['payment_status'] = 'Unpaid';
    }

    // Gán dữ liệu vào đối tượng Order và lưu
    $order->fill($order_data);
    session(['order_id' => $order->id, 'order_number' => $order->order_number, 'order_status' => $order->payment_status]);
    
    // Kiểm tra nếu đơn hàng được lưu thành công
    if ($order->save()) {
        // Cập nhật giỏ hàng với order_id mới
        Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => $order->id]);

        // Thông báo thành công cho người dùng
        request()->session()->flash('success', 'Đơn đặt hàng sản phẩm của bạn đã được đặt. Cảm ơn bạn đã mua sắm với chúng tôi.');
        
        // Nếu phương thức thanh toán là Paypal, chuyển hướng đến trang thanh toán
        if (request('payment_method') == 'paypal') {
            return redirect()->route('payment')->with(['id' => $order->id]);
        } elseif (request('payment_method') == 'qrpay') {
            // Nếu thanh toán bằng QRPay, chuyển đến trang thanh toán QRPay
            return redirect()->route('qrpayment', [
                'id' => $order->id, 
                'order_number' => $order->order_number, 
                'total_amount' => $order->total_amount
            ]);
        } else {
            // Xóa giỏ hàng sau khi đặt hàng thành công
            session()->forget('cart');
        }
        

        // Chuyển hướng đến trang chủ hoặc trang khác
        return redirect()->route('home');
    } else {
        // Xử lý nếu đơn hàng không thể lưu
        dd('Error: Failed to save order.');
    }
}
public function qrPayment($id, $order_number, $total_amount)
{
    // Lấy thông tin đơn hàng từ ID
    $order = Order::find($id);

    // Kiểm tra nếu không tìm thấy đơn hàng
    if (!$order) {
        request()->session()->flash('error', 'Không tìm thấy đơn hàng!');
        return redirect()->route('home');
    }

    // Truyền dữ liệu vào view
    return view('frontend.pages.qrpayment', [
        'id' => $id, 
        'order_number' => $order_number, 
        'total_amount' => $total_amount
    ]);
}


public function confirmQrPayment(Request $request, $id)
{
    // Lấy thông tin đơn hàng
    $order = Order::find($id);

    if (!$order) {
        return redirect()->route('order.index')->with('error', 'Không tìm thấy đơn hàng.');
    }

    // Cập nhật trạng thái thanh toán
    $order->payment_status = 'paid';
    $order->save();

    // Gửi thông báo cho người dùng (nếu cần)
    request()->session()->flash('success', 'Xác nhận thanh toán thành công!');

    // Chuyển hướng về trang chi tiết đơn hàng
    return redirect()->route('order.show', $id);
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order=Order::find($id);
        // return $order;
        return view('backend.order.show')->with('order',$order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order=Order::find($id);
        return view('backend.order.edit')->with('order',$order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order=Order::find($id);
        $this->validate($request,[
            'status'=>'required|in:new,process,delivered,cancel'
        ]);
        $data=$request->all();
        // return $request->status;
        if($request->status=='delivered'){
            foreach($order->cart as $cart){
                $product=$cart->product;
                // return $product;
                $product->stock -=$cart->quantity;
                $product->save();
            }
        }
        $status=$order->fill($data)->save();
        if($status){
            request()->session()->flash('success','Successfully updated order');
        }
        else{
            request()->session()->flash('error','Error while updating order');
        }
        return redirect()->route('order.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order=Order::find($id);
        if($order){
            $status=$order->delete();
            if($status){
                request()->session()->flash('success','Order Successfully deleted');
            }
            else{
                request()->session()->flash('error','Order can not deleted');
            }
            return redirect()->route('order.index');
        }
        else{
            request()->session()->flash('error','Order can not found');
            return redirect()->back();
        }
    }

    public function orderTrack(){
        return view('frontend.pages.order-track');
    }

    public function productTrackOrder(Request $request){
        // return $request->all();
        $order=Order::where('user_id',auth()->user()->id)->where('order_number',$request->order_number)->first();
        if($order){
            if($order->status=="new"){
            request()->session()->flash('success','Đơn hàng của bạn đã được đặt.');
            return redirect()->route('home');

            }
            elseif($order->status=="process"){
                request()->session()->flash('success','Đơn hàng của bạn đang được xử lý.');
                return redirect()->route('home');

            }
            elseif($order->status=="delivered"){
                request()->session()->flash('success','Đơn hàng của bạn đã được giao.');
                return redirect()->route('home');

            }
            else{
                request()->session()->flash('error','Đơn hàng của bạn đã bị hủy.');
                return redirect()->route('home');

            }
        }
        else{
            request()->session()->flash('error','Mã đơn hàng không tồn tại.');
            return back();
        }
    }

    // PDF generate
    public function pdf(Request $request){
        $order=Order::getAllOrder($request->id);
        // return $order;
        $file_name=$order->order_number.'-'.$order->first_name.'.pdf';
        // return $file_name;
        $pdf=PDF::loadview('backend.order.pdf',compact('order'));
        return $pdf->download($file_name);
    }


    // Income chart
    public function incomeChart(Request $request){
        $year=\Carbon\Carbon::now()->year;
        // dd($year);
        $items=Order::with(['cart_info'])->whereYear('created_at',$year)->where('status','delivered')->get()
            ->groupBy(function($d){
                return \Carbon\Carbon::parse($d->created_at)->format('m');
            });
            // dd($items);
        $result=[];
        foreach($items as $month=>$item_collections){
            foreach($item_collections as $item){
                $amount=$item->cart_info->sum('amount');
                // dd($amount);
                $m=intval($month);
                // return $m;
                isset($result[$m]) ? $result[$m] += $amount :$result[$m]=$amount;
            }
        }
        $data=[];
        for($i=1; $i <=12; $i++){
            $monthName=date('F', mktime(0,0,0,$i,1));
            $data[$monthName] = (!empty($result[$i]))? number_format((float)($result[$i]), 2, '.', '') : 0.0;
        }
        return $data;
    }

    public function reportgetSalesList(Request $request)
    {
        $year = $request->year ?? \Carbon\Carbon::now()->year; // Lấy năm từ request hoặc mặc định là năm hiện tại
        $month = $request->month ?? \Carbon\Carbon::now()->month; // Lấy tháng từ request hoặc mặc định là tháng hiện tại

        // Lấy tất cả các đơn hàng đã giao trong năm và tháng được chọn
        $orders = Order::with(['cart_info.product'])
                       ->whereYear('created_at', $year)
                       ->whereMonth('created_at', $month)
                       ->where('status', 'delivered')
                       ->get();

        $productSales = []; // Mảng chứa thông tin sản phẩm bán được

        // Lặp qua các đơn hàng để tính tổng số lượng và doanh thu của từng sản phẩm
        foreach ($orders as $order) {
            foreach ($order->cart_info as $cart) {
                $product = $cart->product;

                // Tính tổng tiền cho sản phẩm trong giỏ hàng
                $totalAmountForProduct = $cart->quantity * $cart->price; // Giả sử mỗi sản phẩm có giá là price

                // Nếu sản phẩm đã có trong mảng, cộng dồn số lượng và doanh thu
                if (isset($productSales[$product->id])) {
                    $productSales[$product->id]['quantity'] += $cart->quantity;
                    $productSales[$product->id]['total_amount'] += $totalAmountForProduct;
                } else {
                    // Nếu chưa có sản phẩm trong mảng, thêm mới
                    $productSales[$product->id] = [
                        'title' => $product->title,
                        'quantity' => $cart->quantity,
                        'total_amount' => $totalAmountForProduct,
                    ];
                }
            }
        }

        // Chuyển đổi dữ liệu thành mảng dễ sử dụng trong view
        $data = [];
        foreach ($productSales as $productId => $saleData) {
            $data[] = [
                'title' => $saleData['title'],
                'quantity' => $saleData['quantity'],
                'total_amount' => number_format((float)$saleData['total_amount'], 2, '.', ''), // Định dạng tiền
            ];
        }

        // Trả về kết quả dưới dạng dữ liệu cho view
        return view('backend.report.index', compact('data', 'year', 'month'));
    }


public function export(Request $request)
{
    $year = $request->year ?? \Carbon\Carbon::now()->year; // Lấy năm từ request hoặc mặc định là năm hiện tại
    $month = $request->month ?? \Carbon\Carbon::now()->month; // Lấy tháng từ request hoặc mặc định là tháng hiện tại

    // Lấy tất cả các đơn hàng đã được giao trong năm và tháng được chọn
    $orders = Order::with(['cart_info.product'])
                   ->whereYear('created_at', $year)
                   ->whereMonth('created_at', $month)
                   ->where('status', 'delivered')
                   ->get();

    $productSales = []; // Mảng chứa thông tin sản phẩm bán được

    // Lặp qua các đơn hàng để tính tổng số lượng và doanh thu của từng sản phẩm
    foreach ($orders as $order) {
        foreach ($order->cart_info as $cart) {
            $product = $cart->product;

            // Nếu sản phẩm đã có trong mảng, cộng dồn số lượng và doanh thu
            if (isset($productSales[$product->id])) {
                $productSales[$product->id]['quantity'] += $cart->quantity;
                $productSales[$product->id]['total_amount'] += $order->total_amount;
            } else {
                // Nếu chưa có sản phẩm trong mảng, thêm mới
                $productSales[$product->id] = [
                    'title' => $product->title,
                    'quantity' => $cart->quantity,
                    'total_amount' => $order->total_amount,
                ];
            }
        }
    }

    // Chuyển dữ liệu thành danh sách cần thiết cho file Excel
    $data = [];
    foreach ($productSales as $productId => $saleData) {
        $data[] = [
            'title' => $saleData['title'],
            'quantity' => $saleData['quantity'],
            'total_amount' => number_format((float)$saleData['total_amount'], 2, '.', ''),
        ];
    }

    // Xuất dữ liệu ra file Excel
    return Excel::download(new OrderReportExport($data), "order_report_{$month}_{$year}.xlsx");
}
public function exportProductDetails($title, $month, $year)
{
    // Lọc sản phẩm theo tên
    $product = Product::where('title', $title)->first();

    if (!$product) {
        // Nếu không tìm thấy sản phẩm, trả về lỗi hoặc thông báo
        return redirect()->back()->with('error', 'Sản phẩm không tồn tại.');
    }

    // Lấy thông tin các đơn hàng trong tháng và năm
    $orders = Order::whereMonth('created_at', $month)
                   ->whereYear('created_at', $year)
                   ->where('status', 'delivered')
                   ->get();

    // Chuyển đổi dữ liệu thành định dạng phù hợp để xuất Excel
    $data = $orders->map(function ($order) use ($product) {
        // Lấy thông tin Cart liên kết với Order
        $cart = Cart::where('order_id', $order->id)->get(); // Lấy tất cả các Cart liên quan đến Order

        // Tạo một mảng để lưu tất cả sản phẩm trong đơn hàng
        $orderProducts = [];

        foreach ($cart as $item) {
            // Kiểm tra nếu sản phẩm trong Cart là sản phẩm cần tìm
            if ($item->product_id == $product->id) {
                $orderProducts[] = (object)[
                    'id' => $order->id,
                    'created_at' => $order->created_at->format('d-m-Y'),
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'amount' => $item->quantity * $item->price,
                ];
            }
        }

        // Nếu có sản phẩm trong giỏ hàng, trả về mảng các sản phẩm
        return $orderProducts;
    })->flatten(1); // Flatten mảng 2 chiều thành 1 chiều

    // Tạo file Excel và trả về
    return Excel::download(new ProductDetailsExport($data), 'chi-tiet-san-pham.xlsx');
}


}
