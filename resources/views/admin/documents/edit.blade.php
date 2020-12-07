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
            {!! Form::model($document, ['route' => ['admin.documents.update', $document->id], "enctype" => "multipart/form-data", 'method' => 'patch']) !!}

            @include('admin.documents.fields')

            {!! Form::close() !!}
        </div>
    </div>
@endsection
