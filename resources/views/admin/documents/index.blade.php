@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Danh sách tài liệu số</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('admin.documents.create') }}">Thêm mới</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row dataTables_filter">
                    <div class="col-sm-3 form-group">
                        <label>Tìm kiếm tài liệu:</label>
                        <input id="filter_query" class="form-control" placeholder="-- Nhập từ khóa tìm kiếm --" value="{{ session()->get('terminal_batch_filter_query') }}">
                    </div>
                    <div class="col-sm-3 form-group">
                        <label>Thời gian tạo:</label>
                        <input class="form-control drp" id="date_range_search" placeholder="-- Chọn ngày tìm kiếm --">
                    </div>
                    <div class="col-sm-3 form-group">
                        <label>Sắp xếp:</label>
                        <select class="form-control" id="order_search">
                            <option value="newest">Mới nhất</option>
                            <option value="mostview">Xem nhiều nhất</option>
                            <option value="mostdownload">Tải nhiều nhất</option>
                        </select>
                    </div>
                </div>
                @include('admin.documents.table')
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
@endsection

