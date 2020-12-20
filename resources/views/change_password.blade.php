@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                {!! Form::model($user ?? '', ['route' => ['change_password'], 'method' => 'post']) !!}

                <div class="row">
                    <div class="form-group col-md-4 @if($errors->has('old_password')) has-error @endif">
                        {!! Form::label('old_password', 'Mật khẩu cũ:') !!}
                        {!! Form::password('old_password', ['class' => 'form-control','maxlength' => 191,'maxlength' => 191]) !!}
                        @if($errors->has('old_password'))
                            <div class="help-block">{{ $errors->first('old_password') }}</div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4 @if($errors->has('new_password')) has-error @endif">
                        {!! Form::label('new_password', 'Mật khẩu mới:') !!}
                        {!! Form::password('new_password', ['class' => 'form-control','maxlength' => 191,'maxlength' => 191]) !!}
                        @if($errors->has('new_password'))
                            <div class="help-block">{{ $errors->first('new_password') }}</div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4 @if($errors->has('re_new_password')) has-error @endif">
                        {!! Form::label('re_new_password', 'Nhập lại mật khẩu mới:') !!}
                        {!! Form::password('re_new_password', ['class' => 'form-control','maxlength' => 191,'maxlength' => 191]) !!}
                        @if($errors->has('re_new_password'))
                            <div class="help-block">{{ $errors->first('re_new_password') }}</div>
                        @endif
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Thay đổi</button>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
