@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Cập nhật Cấp độ bảo mật
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($privacy, ['route' => ['admin.privacy.update', $privacy->id], 'method' => 'patch']) !!}

                        @include('admin.privacy.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
