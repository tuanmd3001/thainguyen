<div class="col-md-6">
    <!-- Name Field -->
    <div class="form-group">
        {!! Form::label('username', 'Tên đăng nhập:') !!}
        <p>{{ $user->username }}</p>
    </div>
</div>
<div class="col-md-6">
    <!-- Name Field -->
    <div class="form-group">
        {!! Form::label('name', 'Tên:') !!}
        <p>{{ $user->name }}</p>
    </div>
</div>
<div class="col-md-6">
    <!-- Email Field -->
    <div class="form-group">
        {!! Form::label('email', 'Email:') !!}
        <p>{{ $user->email }}</p>
    </div>
</div>
@if(\Illuminate\Support\Facades\Auth::guard('admins')->user()->hasRole('SuperAdmin'))
    <div class="col-md-12">
        <a href="{{ route('admin.reset_user_password', $user->id) }}" class="btn btn-primary">Đặt lại mật khẩu</a>
    </div>
@endif

