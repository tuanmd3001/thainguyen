@extends('admin.layouts.app')

@section('content')
    <section class="content-header" style="margin-bottom: 20px">
        <h1 class="pull-left">Danh sách thẻ tag</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="box box-default">
                        <div class="box-body">
                            {!! Form::open(['route' => 'admin.tags.store', 'id' => 'tag-form', "accept-charset" => "UTF-8"]) !!}
                                <div class="card-body">
                                    <div class="form-group @if($errors->has('name')) has-error @endif">
                                        {!! Form::label('name', 'Tên Thẻ:') !!}
                                        {!! Form::text('name', null, ['class' => 'form-control','maxlength' => 191, 'placeholder'=>"Nhập tên thẻ.", "autocomplete"=>"off", "autofocus"=>true ]) !!}
                                        @if($errors->has('name'))
                                            <div class="help-block">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button id="btn-reset" onclick="reset_form()" type="button" class="btn btn-warning pull-left" style="display: none">Hủy</button>
                                    <button id="btn-submit" type="submit" class="btn btn-info pull-right">Thêm thẻ</button>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="box box-default">
                        <div class="box-body">
                            @include('admin.tags.table')
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function edit_tag(id, name){
            let update_route = '{{ route('admin.tags.update', 'tagId') }}'
            let form = $('#tag-form')
            form.prop('action', update_route.replace('tagId', id));
            form.find('input[name="_method"]').remove()
            form.append('<input name="_method" type="hidden" value="PATCH">');
            $('#tag-form input[name="name"]').val(name).focus();
            $('#tag-form #btn-reset').show();
            $('#tag-form #btn-submit').text('Cập nhật');
        }
        function reset_form(){
            $('#tag-form').prop('action', '{{ route('admin.tags.store') }}')
                .find('input[name="_method"]').remove();
            $('#tag-form input[name="name"]').val("");
            $('#tag-form #btn-reset').hide();
            $('#tag-form #btn-submit').text('Thêm thẻ');
        }
    </script>
@endpush

