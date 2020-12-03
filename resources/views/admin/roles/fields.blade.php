<!-- Name Field -->
<div class="form-group col-sm-6 @if($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Tên vai trò:') !!}
    {!! Form::text('name', null, ['class' => 'form-control','maxlength' => 191,'maxlength' => 191]) !!}
    @if($errors->has('name'))
        <div class="help-block">{{ $errors->first('name') }}</div>
    @endif
</div>

<div class="col-md-6 form-group @if($errors->has('permissions')) has-error @endif">
    <label>
        Quyền
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
                        @elseif(isset($role))
                            @if(in_array($permission->name, $rolePermissions))
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

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Lưu', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.roles.index') }}" class="btn btn-default">Quay lại</a>
</div>
