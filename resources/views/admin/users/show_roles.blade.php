<div class="form-group col-md-6">
    <label>
        Vai trò
    </label>
    @foreach($user->roles()->get() as $role)
        <div>
            <div class="label label-primary">{{$role->name}}</div>
        </div>
    @endforeach
</div>
<div class="form-group col-md-6">
    <label>
        Quyền bổ sung (ngoài những quyền theo vai trò)
    </label>
    <div class="permission_selector">
        @foreach($permissions as $permission)
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="{{$permission->name}}" disabled
                           @if(in_array($permission->name, $userPermissions))
                           checked
                        @endif
                    >
                    {{$permission->display_name}}
                </label>
            </div>
        @endforeach
    </div>
</div>
