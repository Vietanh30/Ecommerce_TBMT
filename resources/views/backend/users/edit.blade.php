@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Chỉnh Sửa Người Dùng</h5>
    <div class="card-body">
      <form method="post" action="{{route('users.update', $user->id)}}">
        @csrf 
        @method('PATCH')

        <div class="form-group">
          <label for="inputName" class="col-form-label">Tên</label>
          <input id="inputName" type="text" name="name" placeholder="Nhập tên" value="{{$user->name}}" class="form-control">
          @error('name')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
            <label for="inputEmail" class="col-form-label">Email</label>
            <input id="inputEmail" type="email" name="email" placeholder="Nhập email" value="{{$user->email}}" class="form-control">
            @error('email')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="inputPhoto" class="col-form-label">Ảnh</label>
            <div class="input-group">
                <span class="input-group-btn">
                    <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                    <i class="fa fa-picture-o"></i> Chọn
                    </a>
                </span>
                <input id="thumbnail" class="form-control" type="text" name="photo" value="{{$user->photo}}">
            </div>
            <img id="holder" style="margin-top:15px;max-height:100px;">
            @error('photo')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

        @php 
        $roles = ['admin', 'user'];  // Định nghĩa các vai trò, có thể truy vấn từ cơ sở dữ liệu nếu cần.
        @endphp
        <div class="form-group">
            <label for="role" class="col-form-label">Vai Trò</label>
            <select name="role" class="form-control">
                <option value="">-----Chọn Vai Trò-----</option>
                @foreach($roles as $role)
                    <option value="{{$role}}" {{ $user->role == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
            @error('role')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="status" class="col-form-label">Trạng Thái</label>
            <select name="status" class="form-control">
                <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Hoạt Động</option>
                <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Không Hoạt Động</option>
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

@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script>
    $('#lfm').filemanager('image');
</script>
@endpush
