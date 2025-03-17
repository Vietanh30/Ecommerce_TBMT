@extends('user.layouts.master')

@section('title','Order Detail')

@section('main-content')
<div class="card">
<h5 class="card-header">Đơn hàng       <a href="{{route('order.pdf',$order->id)}}" class=" btn btn-sm btn-primary shadow-sm float-right"><i class="fas fa-download fa-sm text-white-50"></i> Xuất file PDF</a>
  </h5>
  <div class="card-body">
    @if($order)
    <table class="table table-striped table-hover table-hover">
      <thead>
        <tr>
            <th>#</th>
            <th>Mã đơn</th>
            <th>Họ Tên Khách hàng</th>
            <th>Email</th>
            <th>Số lượng</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
            <td>{{$order->id}}</td>
            <td>{{$order->order_number}}</td>
            <td>{{$order->fullname}}</td>
            <td>{{$order->email}}</td>
            <td>{{$order->quantity}}</td>
            <td>{{number_format($order->total_amount,0, ',', '.') }} ₫</td>
            <td>
                @if($order->status=='new')
                  <span class="badge badge-primary">NEW</span>
                @elseif($order->status=='process')
                  <span class="badge badge-warning">PROCESSING</span>
                @elseif($order->status=='delivered')
                  <span class="badge badge-success">DELIVERED</span>
                @else
                  <span class="badge badge-danger">{{$order->status}}</span>
                @endif
            </td>
            <td>
                <form method="POST" action="{{route('order.destroy',[$order->id])}}">
                  @csrf
                  @method('delete')
                      <button class="btn btn-danger btn-sm dltBtn" data-id={{$order->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                </form>
            </td>

        </tr>
      </tbody>
    </table>

    <section class="confirmation_part section_padding">
      <div class="order_boxes">
        <div class="row">
          <div class="col-lg-6 col-lx-4">
            <div class="order-info">
              <h4 class="text-center pb-4">Thông tin đơn hàng</h4>
              <table class="table">
                    <tr class="">
                        <td>Mã đơn hàng</td>
                        <td> : {{$order->order_number}}</td>
                    </tr>
                    <tr>
                        <td>Ngày đặt</td>
                        <td> : {{$order->created_at->format('D d M, Y')}} at {{$order->created_at->format('g : i a')}} </td>
                    </tr>
                    <tr>
                        <td>Số lượng</td>
                        <td> : {{$order->quantity}}</td>
                    </tr>
                    <tr>
                        <td>Tình trạng</td>
                        <td> : {{$order->status}}</td>
                    </tr>

                    <tr>
                        <td>Tổng tiền</td>
                        <td>: {{ number_format($order->total_amount, 0, ',', '.') }} ₫</td>
                    </tr>
                    <tr>
                      <td>Phương thức thanh toán</td>
                      <td> :
                            @if($order->payment_method == 'cod')
                                Thanh toán khi nhận hàng
                            @elseif($order->payment_method == 'paypal')
                                Paypal
                            @elseif($order->payment_method == 'cardpay')
                                Card Payment
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Trạng thái thanh toán</td>
                        <td> :
                          @if($order->payment_status == 'paid')
                              <span class="badge badge-success">Đã thanh toán</span>
                          @elseif($order->payment_status == 'unpaid')
                              <span class="badge badge-danger">Chưa thanh toán</span>
                          @else
                              {{$order->payment_status}}
                          @endif
                      </td>
                    </tr>
              </table>
            </div>
          </div>

          <div class="col-lg-6 col-lx-4">
            <div class="shipping-info">
              <h4 class="text-center pb-4">Thông tin vận chuyển</h4>
              <table class="table">
                    <tr class="">
                        <td>Họ tên khách hàng</td>
                        <td> : {{$order->fullname}}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td> : {{$order->email}}</td>
                    </tr>
                    <tr>
                        <td>Số điện thoại</td>
                        <td> : {{$order->phone}}</td>
                    </tr>
                    <tr>
                        <td>Địa chỉ</td>
                        <td> : {{$order->address}}</td>
                    </tr>

              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    @endif

  </div>
</div>
@endsection

@push('styles')
<style>
    .order-info,.shipping-info{
        background:#ECECEC;
        padding:20px;
    }
    .order-info h4,.shipping-info h4{
        text-decoration: underline;
    }

</style>
@endpush
