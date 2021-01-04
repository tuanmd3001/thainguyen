<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-tag"></i> Thông tin cơ bản</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <!-- Name Field -->
                <div class="form-group @if($errors->has('username')) has-error @endif">
                    {!! Form::label('username', 'Tên đăng nhập:') !!}
                    {!! Form::text('username', null, ['class' => 'form-control','maxlength' => 191,'maxlength' => 191]) !!}
                    @if($errors->has('username'))
                        <div class="help-block">{{ $errors->first('username') }}</div>
                    @endif
                </div>
            </div>
            @if (!isset($user))
                <div class="col-md-6">
                    <!-- Name Field -->
                    <div class="form-group @if($errors->has('password')) has-error @endif">
                        {!! Form::label('password', 'Mật khẩu:') !!}
                        {!! Form::password('password', ['class' => 'form-control','maxlength' => 191,'maxlength' => 191]) !!}
                        @if($errors->has('password'))
                            <div class="help-block">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                </div>
            @endif
            <div class="col-md-6">
                <!-- Name Field -->
                <div class="form-group @if($errors->has('name')) has-error @endif">
                    {!! Form::label('name', 'Tên:') !!}
                    {!! Form::text('name', null, ['class' => 'form-control','maxlength' => 191,'maxlength' => 191]) !!}
                    @if($errors->has('name'))
                        <div class="help-block">{{ $errors->first('name') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <!-- Name Field -->
                <div class="form-group @if($errors->has('email')) has-error @endif">
                    {!! Form::label('email', 'Email:') !!}
                    {!! Form::email('email', null, ['class' => 'form-control','maxlength' => 191,'maxlength' => 191]) !!}
                    @if($errors->has('email'))
                        <div class="help-block">{{ $errors->first('email') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-tag"></i> Thông tin vai trò và quyền</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-6 form-group @if($errors->has('roles')) has-error @endif">
                <label for="role_select">
                    Vai trò
                </label>
                <select id="role_select" class="form-control select2-multiple" name="roles[]" multiple="multiple">
                    @foreach($roles as $role)
                        <option @if (isset($user) && $user->hasrole($role->name)) selected @endif value="{{$role->name}}">{{$role->name}}</option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                    <div class="help-block">{{ $errors->first('roles') }}</div>
                @endif
            </div>
            <div class="col-md-6 form-group @if($errors->has('permissions')) has-error @endif">
                <label>
                    Quyền bổ sung (ngoài những quyền theo vai trò)
                </label>
                <div class="permission_selector">
                    @foreach($permissions as $permission)
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="permissions[]" value="{{$permission->name}}"
                                    @if(old("permissions"))
                                        @if(in_array($permission->name, old("permissions")))
                                            checked
                                        @endif
                                    @elseif(isset($user))
                                        @if(in_array($permission->name, $userPermissions))
                                            checked
                                        @endif
                                    @endif
                                >
                                {{$permission->display_name}}
                            </label>
                        </div>
                    @endforeach
                </div>
                @if($errors->has('permissions'))
                    <div class="help-block">{{ $errors->first('permissions') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2-multiple').select2();
        });
    </script>
@endpush
<!-- Submit Field -->
<div class="form-group row">
    <div class="col-sm-12">
        {!! Form::submit('Lưu', ['class' => 'btn btn-primary']) !!}
        <a href="{{ route('admin.adminUsers.index') }}" class="btn btn-default">Quay lại</a>
    </div>
</div>
