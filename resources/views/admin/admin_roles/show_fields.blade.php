<div class="row">
    <!-- Name Field -->
    <div class="form-group col-md-6">
        {!! Form::label('name', 'Tên vai trò:') !!}
        <p>{{ $role->name }}</p>
    </div>


    <div class="form-group col-md-6">
        <label>
            Quyền
        </label>
        <div class="permission_selector">
            @foreach($permissions as $permission)
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="permissions[]" value="{{$permission->name}}" disabled
                               @if(in_array($permission->name, $rolePermissions))
                               checked
                            @endif
                        >
                        {{$permission->display_name}}
                    </label>
                </div>
            @endforeach
        </div>
    </div>
</div>

