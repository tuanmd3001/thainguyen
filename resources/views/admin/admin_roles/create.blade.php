@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Thêm mới vai trò người dùng
        </h1>
    </section>
    <div class="content">
{{--        @include('adminlte-templates::common.errors')--}}
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.adminRoles.store']) !!}

                        @include('admin.admin_roles.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
