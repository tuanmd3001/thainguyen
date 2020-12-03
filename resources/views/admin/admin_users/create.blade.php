@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Thêm mới quản trị viên
        </h1>
    </section>
    <div class="content">
{{--        @include('adminlte-templates::common.errors')--}}
        {!! Form::open(['route' => 'admin.adminUsers.store']) !!}

        @include('admin.admin_users.fields')

        {!! Form::close() !!}
    </div>
@endsection
