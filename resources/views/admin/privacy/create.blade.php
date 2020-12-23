@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Thêm mới Cấp độ bảo mật
        </h1>
    </section>
    <div class="content">
{{--        @include('adminlte-templates::common.errors')--}}
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.privacy.store']) !!}

                        @include('admin.privacy.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
