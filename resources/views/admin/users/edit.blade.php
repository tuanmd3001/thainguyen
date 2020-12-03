@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Cập nhật thông tin người đọc
        </h1>
    </section>
    <div class="content">
{{--        @include('adminlte-templates::common.errors')--}}

        {!! Form::model($user, ['route' => ['admin.users.update', $user->id], 'method' => 'patch']) !!}

        @include('admin.users.fields')

        {!! Form::close() !!}
    </div>
@endsection
