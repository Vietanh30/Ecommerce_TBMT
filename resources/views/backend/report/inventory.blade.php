@extends('backend.layouts.master')

@section('main-content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Thống Kê Tồn Kho</h6>
    </div>
    <div class="card-body">
        <!-- Form lọc -->
        <form action="{{ route('product.inventory') }}" method="get" class="form-inline mb-3">
    <label for="category_id" class="mr-2">Chọn Loại Sản Phẩm:</label>
    <select name="category_id" id="category_id" class="form-control mr-3">
        <option value="">Tất cả</option>
        @foreach(App\Models\Category::all() as $category)
            <option value="{{ $category->id }}" {{ (isset($categoryId) && $category->id == $categoryId) ? 'selected' : '' }}>
                {{ $category->title }}
            </option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-search"></i> Lọc
    </button>

    <!-- Nút Xuất Excel -->
    <a href="{{ route('product.inventory.export', ['category_id' => request('category_id')]) }}"
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
                        <th>Loại Sản Phẩm</th>
                        <th>Tồn Kho</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $product->title }}</td>
                        <td>{{ $product->cat_info->title ?? 'Không rõ' }}</td>
                        <td>{{ $product->stock }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
