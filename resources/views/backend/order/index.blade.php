@extends('backend.layouts.master')

@section('main-content')
 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="row">
         <div class="col-md-12">
            @include('backend.layouts.notification')
         </div>
     </div>
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">Danh sách Đơn Hàng</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        @if(count($orders) > 0)
        <table class="table table-bordered table-hover" id="order-dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>#</th>
              <th>Mã Đơn Hàng</th>
              <th>Họ và Tên</th>
              <th>Email</th>
              <th>Số lượng</th>
              <th>Phí Vận Chuyển</th>
              <th>Tổng Cộng</th>
              <th>Trạng Thái</th>
              <th>Hành Động</th>
            </tr>
          </thead>
          <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{$order->id}}</td>
                    <td>{{$order->order_number}}</td>
                    <td>{{$order->fullname}}</td>
                    <td>{{$order->email}}</td>
                    <td>{{$order->quantity}}</td>
                    <td>${{number_format($order->shipping->price ?? 0, 2)}}</td> <!-- Using relationship for shipping -->
                    <td>${{number_format($order->total_amount, 2)}}</td>
                    <td>
                        @switch($order->status)
                            @case('new')
                                <span class="badge badge-primary">Mới</span>
                                @break
                            @case('process')
                                <span class="badge badge-warning">Đang xử lý</span>
                                @break
                            @case('delivered')
                                <span class="badge badge-success">Đã giao</span>
                                @break
                            @case('cancel')
                                <span class="badge badge-danger">Hủy</span>
                                @break
                            @default
                                <span class="badge badge-secondary">Chưa xác định</span>
                        @endswitch
                    </td>
                    <td>
                        <a href="{{route('order.show', $order->id)}}" class="btn btn-warning btn-sm" title="Xem" data-toggle="tooltip" data-placement="bottom"><i class="fas fa-eye"></i></a>
                        <a href="{{route('order.edit', $order->id)}}" class="btn btn-primary btn-sm" title="Chỉnh sửa" data-toggle="tooltip" data-placement="bottom"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{route('order.destroy', [$order->id])}}" class="d-inline">
                          @csrf
                          @method('delete')
                          <button class="btn btn-danger btn-sm dltBtn" data-id={{$order->id}} title="Xóa" data-toggle="tooltip" data-placement="bottom"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
          </tbody>
        </table>
        <span style="float:right">{{$orders->links()}}</span>
        @else
          <h6 class="text-center">Không có đơn hàng nào. Vui lòng tạo đơn hàng mới.</h6>
        @endif
      </div>
    </div>
</div>
@endsection

@push('styles')
  <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
      div.dataTables_wrapper div.dataTables_paginate {
          display: none;
      }
  </style>
@endpush

@push('scripts')
  <!-- Page level plugins -->
  <script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="{{asset('backend/js/demo/datatables-demo.js')}}"></script>

  <script>
      $(document).ready(function(){
          $('#order-dataTable').DataTable({
              "columnDefs": [
                  {
                      "orderable": false,
                      "targets": [8]
                  }
              ]
          });

          // SweetAlert for delete confirmation
          $('.dltBtn').click(function(e){
              var form = $(this).closest('form');
              e.preventDefault();
              swal({
                  title: "Bạn có chắc chắn không?",
                  text: "Sau khi xóa, bạn sẽ không thể khôi phục lại dữ liệu này!",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
              })
              .then((willDelete) => {
                  if (willDelete) {
                      form.submit();
                  } else {
                      swal("Dữ liệu của bạn đã an toàn!");
                  }
              });
          });
      });
  </script>
@endpush
