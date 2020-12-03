@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Thêm mới người đọc
        </h1>
    </section>
    <div class="content">
{{--        @include('adminlte-templates::common.errors')--}}
        {!! Form::open(['route' => 'admin.users.store']) !!}

        @include('admin.users.fields')

        {!! Form::close() !!}
    </div>
@endsection
