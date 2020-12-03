@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Sửa vai trò người dùng
        </h1>
   </section>
   <div class="content">
{{--       @include('adminlte-templates::common.errors')--}}
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($role, ['route' => ['admin.roles.update', $role->id], 'method' => 'patch']) !!}

                        @include('admin.roles.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
