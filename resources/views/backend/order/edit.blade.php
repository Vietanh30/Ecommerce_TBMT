@extends('backend.layouts.master')

@section('title','Order Detail')

@section('main-content')
<div class="card">
  <h5 class="card-header">Chỉnh Sửa Đơn Hàng</h5>
  <div class="card-body">
    <form action="{{route('order.update',$order->id)}}" method="POST">
      @csrf
      @method('PATCH')
      
      <!-- Chỉnh sửa trạng thái đơn hàng -->
      <div class="form-group">
        <label for="status">Trạng Thái Đơn Hàng:</label>
        <select name="status" id="status" class="form-control">
          <!-- Trạng thái mới -->
          <option value="new" 
                  {{ ($order->status == 'delivered' || $order->status == 'process' || $order->status == 'cancel') ? 'disabled' : '' }} 
                  {{ ($order->status == 'new') ? 'selected' : '' }}>Mới</option>
          
          <!-- Trạng thái đang xử lý -->
          <option value="process" 
                  {{ ($order->status == 'delivered' || $order->status == 'cancel') ? 'disabled' : '' }} 
                  {{ ($order->status == 'process') ? 'selected' : '' }}>Đang xử lý</option>
          
          <!-- Trạng thái đã giao -->
          <option value="delivered" 
                  {{ ($order->status == 'cancel') ? 'disabled' : '' }} 
                  {{ ($order->status == 'delivered') ? 'selected' : '' }}>Đã giao</option>
          
          <!-- Trạng thái hủy -->
          <option value="cancel" 
                  {{ ($order->status == 'delivered') ? 'disabled' : '' }} 
                  {{ ($order->status == 'cancel') ? 'selected' : '' }}>Hủy</option>
        </select>
      </div>

      <!-- Nút cập nhật -->
      <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
  </div>
</div>
@endsection

@push('styles')
<style>
    .order-info, .shipping-info {
        background: #ECECEC;
        padding: 20px;
    }
    .order-info h4, .shipping-info h4 {
        text-decoration: underline;
    }
</style>
@endpush
