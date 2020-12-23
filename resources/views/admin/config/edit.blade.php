@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Cài đặt hệ thống
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')

        {!! Form::model($config, ['route' => ['admin.config.update'], 'method' => 'patch']) !!}

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-cogs"></i> Cài đặt chung</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Name Field -->
                        <div class="form-group @if($errors->has('text_code')) has-error @endif">
                            {!! Form::label('text_code', 'Bảng mã Tiếng Việt:') !!}
                            <select class="form-control select2 select2-hidden-accessible" name="text_code">
                                @foreach(\App\Models\Constants::TEXT_CODE_LABEL as $key => $code)
                                    <option @if (isset($config) && $config->text_code == $key) selected @endif value="{{$key}}">{{$code}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('text_code'))
                                <div class="help-block">{{ $errors->first('text_code') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Name Field -->
                        <div class="form-group @if($errors->has('save_path')) has-error @endif">
                            {!! Form::label('save_path', 'Đường dẫn lưu tệp tin:') !!}
                            {!! Form::text('save_path', null, ['class' => 'form-control','maxlength' => 191,'maxlength' => 191]) !!}
                            @if($errors->has('save_path'))
                                <div class="help-block">{{ $errors->first('save_path') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-clipboard"></i> Nhật ký người đọc</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Name Field -->
                                <div class="form-group @if($errors->has('log_search')) has-error @endif">
                                    {!! Form::label('log_search', 'Ghi nhật ký tìm kiếm:') !!}
                                    <span class="pull-right">
                                <label class="switch">
                                {!! Form::checkbox('log_search', 1, null) !!}
                                <span class="slider round"></span>
                            </label>
                            @if($errors->has('log_search'))
                                            <div class="help-block">{{ $errors->first('log_search') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Name Field -->
                                <div class="form-group @if($errors->has('log_view')) has-error @endif">
                                    {!! Form::label('log_view', 'Ghi nhật ký xem tài liệu:') !!}
                                    <span class="pull-right">
                                <label class="switch">
                                {!! Form::checkbox('log_view', 1, null) !!}
                                <span class="slider round"></span>
                            </label>
                            @if($errors->has('log_view'))
                                            <div class="help-block">{{ $errors->first('log_view') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Name Field -->
                                <div class="form-group @if($errors->has('log_download')) has-error @endif">
                                    {!! Form::label('log_download', 'Ghi nhật ký tải tài liệu:') !!}
                                    <span class="pull-right">
                                <label class="switch">
                                {!! Form::checkbox('log_download', 1, null) !!}
                                <span class="slider round"></span>
                            </label>
                            @if($errors->has('log_download'))
                                            <div class="help-block">{{ $errors->first('log_download') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Name Field -->
                                <div class="form-group @if($errors->has('log_comment')) has-error @endif">
                                    {!! Form::label('log_comment', 'Ghi nhật ký nhận xét tài liệu:') !!}
                                    <span class="pull-right">
                                <label class="switch">
                                {!! Form::checkbox('log_comment', 1, null) !!}
                                <span class="slider round"></span>
                            </label>
                            @if($errors->has('log_comment'))
                                            <div class="help-block">{{ $errors->first('log_comment') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            {!! Form::submit('Lưu cài đặt', ['class' => 'btn btn-primary']) !!}
        </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
