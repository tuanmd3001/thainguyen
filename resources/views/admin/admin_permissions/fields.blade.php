<!-- Name Field -->
<div class="form-group col-sm-6 @if($errors->has('display_name')) has-error @endif">
    {!! Form::label('display_name', 'Tên chức năng:') !!}
    {!! Form::text('display_name', null, ['class' => 'form-control','maxlength' => 191,'maxlength' => 191]) !!}
    @if($errors->has('display_name'))
        <div class="help-block">{{ $errors->first('display_name') }}</div>
    @endif
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Lưu', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.adminPermissions.index') }}" class="btn btn-default">Quay lại</a>
</div>
