@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Chi tiết Cấp độ bảo mật
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.privacy.show_fields')
                    <a href="{{ route('admin.privacy.edit', $privacy->id) }}" class="btn btn-primary">Sửa</a>
                    <a href="{{ route('admin.privacy.index') }}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
