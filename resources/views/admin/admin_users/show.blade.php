@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Thông tin chi tiết quản trị viên
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> Thông tin cơ bản</h3>
            </div>
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.admin_users.show_fields')
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> Thông tin vai trò và quyền</h3>
            </div>
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.admin_users.show_roles')
                </div>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.adminUsers.edit', $user->id) }}" class="btn btn-primary">Sửa</a>
            <a href="{{ route('admin.adminUsers.index') }}" class="btn btn-default">Quay lại</a>
        </div>
    </div>
@endsection
