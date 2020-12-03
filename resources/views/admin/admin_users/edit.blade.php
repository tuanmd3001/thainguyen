@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Cập nhật thông tin quản trị viên
        </h1>
    </section>
    <div class="content">
{{--        @include('adminlte-templates::common.errors')--}}

        {!! Form::model($user, ['route' => ['admin.adminUsers.update', $user->id], 'method' => 'patch']) !!}

        @include('admin.admin_users.fields')

        {!! Form::close() !!}
    </div>
@endsection
