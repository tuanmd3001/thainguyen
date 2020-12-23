<!-- Ten Field -->
<div class="form-group col-md-6 @if($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Tên:') !!}
    {!! Form::text('name', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
    @if($errors->has('name'))
        <div class="help-block">{{ $errors->first('name') }}</div>
    @endif
</div>

<!-- Submit Field -->
<div class="form-group col-md-12">
    {!! Form::submit('Lưu', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.privacy.index') }}" class="btn btn-default">Quay lại</a>
</div>
