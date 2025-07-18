@extends('backend.layouts.master')
@section('title','Ecommerce Laravel || Tạo Nhà Phân Phối')
@section('main-content')

<div class="card">
  <h5 class="card-header">Thêm Nhà Phân Phối</h5>
  <div class="card-body">
    <form method="post" action="{{route('brand.update', $brand->id)}}">
      @csrf
      @method('PUT')
      <div class="form-group">
        <label for="inputTitle" class="col-form-label">Tiêu đề <span class="text-danger">*</span></label>
        <input id="inputTitle" type="text" name="title" placeholder="Nhập tiêu đề" value="{{old('title')}}" class="form-control">
        @error('title')
        <span class="text-danger">{{$message}}</span>
        @enderror
      </div>

      <div class="form-group">
        <label for="status" class="col-form-label">Trạng thái <span class="text-danger">*</span></label>
        <select name="status" class="form-control">
          <option value="active">Hoạt động</option>
          <option value="inactive">Không hoạt động</option>
        </select>
        @error('status')
        <span class="text-danger">{{$message}}</span>
        @enderror
      </div>

      <div class="form-group">
        <label for="description" class="col-form-label">Mô tả</label>
        <textarea id="description" name="description" class="form-control">{{ old('description', $brand->description ?? '') }}</textarea>
      </div>

      <div class="form-group mb-3">
        <button type="reset" class="btn btn-warning">Đặt lại</button>
        <button class="btn btn-success" type="submit">Gửi</button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">
@endpush
@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script>
  $('#lfm').filemanager('image');

  $(document).ready(function() {
    $('#description').summernote({
      placeholder: "Viết mô tả ngắn.....",
      tabsize: 2,
      height: 150
    });
  });
</script>
@endpush