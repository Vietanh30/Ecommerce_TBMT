@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Thêm Danh Mục Bài Viết</h5>
    <div class="card-body">
      <form method="post" action="{{route('post-category.store')}}">
        {{csrf_field()}}
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Tiêu Đề</label>
          <input id="inputTitle" type="text" name="title" placeholder="Nhập tiêu đề"  value="{{old('title')}}" class="form-control">
          @error('title')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="status" class="col-form-label">Trạng Thái</label>
          <select name="status" class="form-control">
              <option value="active">Kích Hoạt</option>
              <option value="inactive">Không Kích Hoạt</option>
          </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group mb-3">
          <button type="reset" class="btn btn-warning">Làm Mới</button>
           <button class="btn btn-success" type="submit">Xác Nhận</button>
        </div>
      </form>
    </div>
</div>

@endsection
