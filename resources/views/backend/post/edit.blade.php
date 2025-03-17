@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Chỉnh Sửa Bài Viết</h5>
    <div class="card-body">
      <form method="post" action="{{route('post.update',$post->id)}}">
        @csrf 
        @method('PATCH')
        
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Tiêu Đề <span class="text-danger">*</span></label>
          <input id="inputTitle" type="text" name="title" placeholder="Nhập tiêu đề"  value="{{$post->title}}" class="form-control">
          @error('title')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="quote" class="col-form-label">Trích Dẫn</label>
          <textarea class="form-control" id="quote" name="quote">{{$post->quote}}</textarea>
          @error('quote')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="summary" class="col-form-label">Tóm Tắt <span class="text-danger">*</span></label>
          <textarea class="form-control" id="summary" name="summary">{{$post->summary}}</textarea>
          @error('summary')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="description" class="col-form-label">Mô Tả</label>
          <textarea class="form-control" id="description" name="description">{{$post->description}}</textarea>
          @error('description')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="post_cat_id">Danh Mục <span class="text-danger">*</span></label>
          <select name="post_cat_id" class="form-control">
              <option value="">--Chọn danh mục--</option>
              @foreach($categories as $key=>$data)
                  <option value='{{$data->id}}' {{(($data->id==$post->post_cat_id)? 'selected' : '')}}>{{$data->title}}</option>
              @endforeach
          </select>
        </div>
        <div class="form-group">
          <label for="added_by">Tác Giả</label>
          <select name="added_by" class="form-control">
              <option value="">--Chọn tác giả--</option>
              @foreach($users as $key=>$data)
                <option value='{{$data->id}}' {{(($post->added_by==$data->id)? 'selected' : '')}}>{{$data->name}}</option>
              @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="inputPhoto" class="col-form-label">Hình Ảnh <span class="text-danger">*</span></label>
          <div class="input-group">
              <span class="input-group-btn">
                  <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                  <i class="fa fa-picture-o"></i> Chọn
                  </a>
              </span>
          <input id="thumbnail" class="form-control" type="text" name="photo" value="{{$post->photo}}">
        </div>
        <div id="holder" style="margin-top:15px;max-height:100px;"></div>
          @error('photo')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        
        <div class="form-group">
          <label for="status" class="col-form-label">Trạng Thái <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
            <option value="active" {{(($post->status=='active')? 'selected' : '')}}>Hoạt Động</option>
            <option value="inactive" {{(($post->status=='inactive')? 'selected' : '')}}>Không Hoạt Động</option>
        </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        
        <div class="form-group mb-3">
           <button class="btn btn-success" type="submit">Cập Nhật</button>
        </div>
      </form>
    </div>
</div>

@endsection
