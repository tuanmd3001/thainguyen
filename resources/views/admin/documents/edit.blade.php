@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Document
        </h1>
   </section>
    <div class="content">
        {{--        @include('adminlte-templates::common.errors')--}}
        <div class="row">
            {!! Form::open(['route' => 'admin.documents.store', "enctype" => "multipart/form-data"]) !!}

            @include('admin.documents.fields')

            {!! Form::close() !!}
        </div>
    </div>
@endsection
