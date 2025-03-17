@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Thêm Bài Viết</h5>
    <div class="card-body">
      <form method="post" action="{{route('post.store')}}">
        {{csrf_field()}}
        
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Tiêu Đề <span class="text-danger">*</span></label>
          <input id="inputTitle" type="text" name="title" placeholder="Nhập tiêu đề" value="{{old('title')}}" class="form-control">
          @error('title')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="quote" class="col-form-label">Trích Dẫn</label>
          <textarea class="form-control" id="quote" name="quote">{{old('quote')}}</textarea>
          @error('quote')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="summary" class="col-form-label">Tóm Tắt <span class="text-danger">*</span></label>
          <textarea class="form-control" id="summary" name="summary">{{old('summary')}}</textarea>
          @error('summary')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="description" class="col-form-label">Mô Tả</label>
          <textarea class="form-control" id="description" name="description">{{old('description')}}</textarea>
          @error('description')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="post_cat_id">Danh Mục <span class="text-danger">*</span></label>
          <select name="post_cat_id" class="form-control">
              <option value="">--Chọn danh mục--</option>
              @foreach($categories as $key=>$data)
                  <option value='{{$data->id}}'>{{$data->title}}</option>
              @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="tags">Thẻ</label>
          <select name="tags[]" multiple data-live-search="true" class="form-control selectpicker">
              <option value="">--Chọn thẻ--</option>
        
          </select>
        </div>

        <div class="form-group">
          <label for="added_by">Tác Giả</label>
          <select name="added_by" class="form-control">
              <option value="">--Chọn tác giả--</option>
              @foreach($users as $key=>$data)
                <option value='{{$data->id}}' {{($key==0) ? 'selected' : ''}}>{{$data->name}}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
    <label for="inputPhoto" class="col-form-label">Hình Ảnh <span class="text-danger">*</span></label>
    <input type="file" id="inputPhoto" name="photo" class="form-control" accept="image/*">
    @error('photo')
        <span class="text-danger">{{$message}}</span>
    @enderror
</div>
<div id="holder" style="margin-top:15px;">
    <img id="preview" src="#" alt="Preview" style="max-height: 100px; display: none;">
</div>


        <div class="form-group">
          <label for="status" class="col-form-label">Trạng Thái <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
              <option value="active">Hoạt Động</option>
              <option value="inactive">Không Hoạt Động</option>
          </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        
        <div class="form-group mb-3">
          <button type="reset" class="btn btn-warning">Đặt Lại</button>
          <button class="btn btn-success" type="submit">Gửi</button>
        </div>
      </form>
    </div>
</div>

@endsection
<script>
    document.getElementById('thumbnail').addEventListener('change', function(e) {
        const url = e.target.value;
        const preview = document.getElementById('holder');
        preview.innerHTML = `<img src="${url}" alt="Preview" style="max-height: 100px;">`;
    });
</script>
<script>
    document.getElementById('inputPhoto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('preview');
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>

