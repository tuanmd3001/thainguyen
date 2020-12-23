@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::guard('admins')->user();
@endphp

@if($user->can('Tags') ||
    $user->can('Documents')
)
    <li class="treeview {{ Request::is(['admin/tags*', 'admin/documents*']) ? 'active' : '' }}">
        <a href="#">
            <i class="fa fa-book"></i>
            <span>Quản lý tài liệu số</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
        </a>
        <ul class="treeview-menu">
            @if($user->can('Documents'))
                <li class="{{ Request::is('admin/documents*') ? 'active' : '' }}">
                    <a href="{!! route('admin.documents.index') !!}">
                        <i class="fa fa-circle-o"></i>
                        <div>Quản lý tài liệu</div>
                    </a>
                </li>
            @endif
            @if($user->can('Tags'))
                <li class="{{ Request::is('admin/tags*') ? 'active' : '' }}">
                    <a href="{!! route('admin.tags.index') !!}">
                        <i class="fa fa-circle-o"></i>
                        <div>Quản lý thẻ Tag</div>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif

@if($user->can('Users') ||
    $user->can('Roles') ||
    $user->can('Permissions') ||
    $user->can('Log')
)
    <li class="treeview {{ Request::is(['admin/users*', 'admin/roles*', 'admin/permissions*', 'admin/activities*']) ? 'active' : '' }}">
        <a href="#">
            <i class="fa fa-users"></i>
            <span>Quản lý người đọc</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
        </a>
        <ul class="treeview-menu">
            @if($user->can('Users'))
                <li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
                    <a href="{!! route('admin.users.index') !!}">
                        <i class="fa fa-circle-o"></i>
                        <div>Danh sách người đọc</div>
                    </a>
                </li>
            @endif
            @if($user->can('Roles'))
                <li class="{{ Request::is('admin/roles*') ? 'active' : '' }}">
                    <a href="{{ route('admin.roles.index') }}">
                        <i class="fa fa-circle-o"></i>
                        <div>Danh sách nhóm người đọc</div>
                    </a>
                </li>
            @endif
            @if($user->can('Permissions'))
                <li class="{{ Request::is('admin/permissions*') ? 'active' : '' }}">
                    <a href="{{ route('admin.permissions.index') }}">
                        <i class="fa fa-circle-o"></i>
                        <div>Danh sách quyền người đọc</div>
                    </a>
                </li>
            @endif
            @if($user->can('Log'))
                <li class="{{ Request::is('admin/activities*') ? 'active' : '' }}">
                    <a href="{{ route('admin.activities.index') }}">
                        <i class="fa fa-circle-o"></i>
                        <div>Nhật ký người đọc</div>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif

@if($user->can('AdminUsers') ||
    $user->can('AdminRoles') ||
    $user->can('adminPermissions')
)
    <li class="treeview {{ Request::is(['admin/adminUsers*', 'admin/adminRoles*', 'admin/adminPermissions*']) ? 'active' : '' }}">
        <a href="#">
            <i class="fa fa-users"></i>
            <span>Quản lý quản trị viên</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
        </a>
        <ul class="treeview-menu">
            @if($user->can('AdminUsers'))
                <li class="{{ Request::is('admin/adminUsers*') ? 'active' : '' }}">
                    <a href="{!! route('admin.adminUsers.index') !!}">
                        <i class="fa fa-circle-o"></i>
                        <div>Danh sách quản trị viên</div>
                    </a>
                </li>
            @endif
            @if($user->can('AdminRoles'))
                <li class="{{ Request::is('admin/adminRoles*') ? 'active' : '' }}">
                    <a href="{{ route('admin.adminRoles.index') }}">
                        <i class="fa fa-circle-o"></i>
                        <div>Vai trò quản trị viên</div>
                    </a>
                </li>
            @endif
            @if($user->can('AdminPermissions'))
                <li class="{{ Request::is('admin/adminPermissions*') ? 'active' : '' }}">
                    <a href="{{ route('admin.adminPermissions.index') }}">
                        <i class="fa fa-circle-o"></i>
                        <div>Quyền quản trị viên</div>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif

@if($user->can('Privacy'))
    <li class="{{ Request::is("admin/privacy") ? 'active' : ''}}">
        <a href="{{ route('admin.privacy.index') }}">
            <i class="fa fa-shield"></i>
            <span>Cấp độ bảo mật</span>
        </a>
    </li>
@endif

@if($user->can('Config'))
    <li class="{{ Request::is("admin/config") ? 'active' : ''}}">
        <a href="{{ route('admin.config.edit') }}">
            <i class="fa fa-cogs"></i>
            <span>Cài đặt hệ thống</span>
        </a>
    </li>
@endif


