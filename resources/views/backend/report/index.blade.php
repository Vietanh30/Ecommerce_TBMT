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
        <!-- Form Lọc -->
        <form action="{{ route('order.reportgetSalesList') }}" method="get" class="form-inline mb-3">
            <label for="month" class="mr-2">Chọn Tháng:</label>
            <select name="month" id="month" class="form-control mr-3">
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $i == $month ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                    </option>
                @endfor
            </select>

            <label for="year" class="mr-2">Chọn Năm:</label>
            <select name="year" id="year" class="form-control mr-3">
                @foreach(range(2020, \Carbon\Carbon::now()->year) as $yearOption)
                    <option value="{{ $yearOption }}" {{ $yearOption == $year ? 'selected' : '' }}>
                        {{ $yearOption }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Xem Báo Cáo
            </button>

            <!-- Nút Xuất Excel -->
            <a href="{{ route('order.export', ['month' => request('month'), 'year' => request('year')]) }}"
               class="btn btn-success ml-2">
                <i class="fas fa-file-excel"></i> Xuất Excel
            </a>
        </form>

        <!-- Bảng hiển thị -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Số Lượng Bán</th>
                        <th>Tổng Doanh Thu</th>
                        <th>Xuất Chi Tiết</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalRevenue = 0; // Biến tính tổng doanh thu
                    @endphp
                    @foreach($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['title'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ number_format($item['total_amount'], 0, ',', '.') }} VND</td>
                        <td>
                            <a href="{{ route('export.product.details', ['title' => $item['title'], 'month' => $month, 'year' => $year]) }}"
                               class="btn btn-info btn-sm">
                                <i class="fas fa-file-excel"></i> Xuất Chi Tiết
                            </a>
                        </td>
                    </tr>
                    @php
                        $totalRevenue += $item['total_amount'];
                    @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">Tổng Doanh Thu:</th>
                        <th>{{ number_format($totalRevenue, 0, ',', '.') }} VND</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
