@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{url('assets/css/spinner.css')}}">
    <link rel="stylesheet" href="{{url('assets/css/ckeditor.css')}}">
@endsection

@section('content')
    <div id="search-container">
        <form id="search_form" method="GET">
            <div class="row">
                <div class="col-xs-12">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Tìm kiếm" name="txtSearch"
                               autocomplete="off"/>
                        <div class="input-group-btn">
                            <button class="btn btn-primary" type="submit">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 15px">
                <div class="col-xs-12">
                    <div class="panel-group" id="accordion">
                        <div class="">
                            <div class="">
                                <div class="">
                                    <a data-toggle="collapse"
                                       data-parent="#accordion"
                                       href="#extra-search"
                                       style="color: #367fa9">
                                        Tìm kiếm nâng cao
                                    </a>
                                </div>
                            </div>
                            <div id="extra-search" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="searchTitle" name="searchTitle" checked>
                                                <label class="form-check-label" for="searchTitle">Tìm kiếm trong tiêu đề</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="searchDesc" name="searchDesc">
                                                <label class="form-check-label" for="searchDesc">Tìm kiếm trong nội dung tài liệu</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="searchFile" name="searchFile">
                                                <label class="form-check-label" for="searchFile">Tìm kiếm trong file đính kèm</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="searchComment" name="searchComment">
                                                <label class="form-check-label" for="searchComment">Tìm kiếm trong nhận xét</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-12">
                                            Thời gian:
                                        </div>
                                        <div class="col-md-12">
                                            <div class="input-daterange input-group" id="datepicker">
                                                <span class="input-group-addon">Từ</span>
                                                <input type="text" class="input-sm form-control" name="start" />
                                                <span class="input-group-addon">Đến</span>
                                                <input type="text" class="input-sm form-control" name="end" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class=" form-group col-md-12">
                                            <div>Thẻ tag:</div>
                                            <select id="tag_selector" class="form-control" name="tags[]" multiple="multiple">
                                                @foreach($tags as $tag)
                                                    <option value="{{$tag->getTranslation('name', 'vi')}}">{{$tag->getTranslation('name', 'vi')}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div>
        <ul class="nav nav-tabs">
            <li><a data-toggle="tab" href="#searchResult">Kết quả tìm kiếm</a></li>
            <li class="active"><a data-toggle="tab" href="#newest">Tài liệu mới nhất</a></li>
            <li><a data-toggle="tab" href="#mostView">Xem nhiều nhất</a></li>
        </ul>
        <div class="tab-content" style="padding-top: 20px">
            <div id="searchResult" class="tab-pane fade">
                <div class="text-left" style="margin-bottom: 20px">
                    <select id="search_order" onchange="onOderChange()">
                        <option value="newest">Mới nhất</option>
                        <option value="mostview">Xem nhiều nhất</option>
                    </select>
                </div>
                <div id="searchResult-container"></div>
                <div id="showMoreSearchBtn" class="uppercase flex items-center justify-center flex-1 font-sans">
                    <a href="javascript:void(0)" onclick="showMore('search')" rel="next" class="block no-underline text-light hover:text-black p-5">
                        Hiển thị thêm
                    </a>
                </div>
            </div>
            <div id="newest" class="tab-pane fade in active">
                <div id="newest-container"></div>
                <div id="showMoreNewestBtn" class="uppercase flex items-center justify-center flex-1 font-sans">
                    <a href="javascript:void(0)" onclick="showMore('newest')" rel="next" class="block no-underline text-light hover:text-black p-5">
                        Hiển thị thêm
                    </a>
                </div>
            </div>
            <div id="mostView" class="tab-pane fade">
                <div id="mostView-container"></div>
                <div id="showMoreMostViewBtn" class="uppercase flex items-center justify-center flex-1 font-sans">
                    <a href="javascript:void(0)" onclick="showMore('mostview')" rel="next" class="block no-underline text-light hover:text-black p-5">
                        Hiển thị thêm
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div id="spinner-container">
        <svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
            <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
        </svg>
    </div>
@endsection

@push('scripts')
    <script>
        $('#tag_selector').select2({
            tags: false
        });
        $('.input-daterange').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy',
            language: 'vi',
            weekStart: 1
        });
    </script>
    <script>
        var search_next_page_url = null;
        var newest_next_page_url = null;
        var mostview_next_page_url = null;
        $( document ).ready(function (){
            var urlParams = getUrlParameter()
            var keys = Object.keys(urlParams);
            var idx = keys.indexOf("");
            if (idx !== -1) {
                keys.splice(idx, 1);
            }
            if (keys.length > 0){
                if (urlParams['tag']){
                    if ($('#tag_selector').find("option[value='" + urlParams['tag'] + "']").length) {
                        $('#tag_selector').val(urlParams['tag']).trigger('change');

                    }
                }
                search();
            } else {
                $('#showMoreSearchBtn').hide();
                searchDocument({
                    type: 'newest'
                }, true);
                searchDocument({
                    type: 'mostview'
                }, true);
            }
        })

        $('#search_form').submit(function (event){
            event.preventDefault();
            search();
        });

        function getUrlParameter() {
            var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i,
                sParam = [];

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');
                sParam[sParameterName[0]] = sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
            return sParam;
        }

        function search(){
            var formdata = $('#search_form').serializeArray();
            var data = {};
            $(formdata).each(function(index, obj){
                if (obj.name.includes('[]')){
                    let objname = obj.name.replace('[]', '', -1)
                    if (data[objname]){
                        data[objname].push(obj.value)
                    }
                    else {
                        data[objname] = [obj.value]
                    }
                }
                else {
                    data[obj.name] = obj.value;
                }
                data['order'] = $('#search_order').val();
            });
            if (validateSearch(data)){
                searchDocument(data, true)
            }
        }

        function validateSearch(data){
            if (data['start'].trim() || data['end'].trim()){
                return true;
            }
            return !!((data['tags'] && data['tags'].length > 0) || (data['txtSearch'].trim() && (data['searchTitle'] || data['searchDesc'] || data['searchFile'] || data['searchComment'])));

        }

        function searchDocument(filters, refresh = false) {
            $.ajax({
                type: "get",
                data: {
                    ...filters,
                    api_token: "{{ auth()->user()->api_token }}"
                },
                cache: false,
                url: "{{ route('api.search') }}",
                beforeSend: function(){
                    showLoading();
                },
                complete: function(){
                    hideLoading();
                },
                error: function (xhr,status,error) {

                },
                success: function (result,status,xhr) {
                    showResult(result, refresh, filters.hasOwnProperty('type') ?  filters.type : 'search')
                }
            });
        }

        function showLoading() {
            $('#spinner-container').show();
        }

        function hideLoading() {
            $('#spinner-container').hide();
        }

        function showResult(data, refresh = false, type = 'search') {
            var docContainer;
            var showMoreBtn;
            var nextPageVar;
            var is_search = false;
            if (type === "newest"){
                docContainer = $('#newest-container');
                showMoreBtn = $('#showMoreNewestBtn');
                nextPageVar = 'newest_next_page_url';
            }
            else if (type === "mostview"){
                docContainer = $('#mostView-container');
                showMoreBtn = $('#showMoreMostViewBtn');
                nextPageVar = 'mostview_next_page_url';
            }
            else {
                docContainer = $('#searchResult-container');
                showMoreBtn = $('#showMoreSearchBtn');
                nextPageVar = 'search_next_page_url';
                is_search = true;
            }

            if (refresh){
                docContainer.empty();
            }
            // if (data.hasOwnProperty('title') && refresh){
            //     docContainer.append('<h1 class="mb-5">' + data.title + '</h1>')
            // }
            if (data.hasOwnProperty('data') && data.data.length > 0){
                for (let i in data.data){
                    docContainer.append(documentHtml(data.data[i]))
                }
            }
            else {
                docContainer.append('<span>Không tìm thấy tài liệu</span>')
            }
            showMoreBtn.hide();
            if (data.hasOwnProperty("next_page_url")){
                window[nextPageVar] = data.next_page_url;
                if (data.next_page_url !== null){
                    showMoreBtn.show();
                }
            }
            if (is_search){
                $('[href="#searchResult"]').tab('show');
            }
        }
        function documentHtml(data){
            return `
            <div class="well">
                <div class="media">
                    <a class="pull-left" href="` + data.doc_url + `">
                        <div class="media-object" style="background-image: url('` + data.thumbnail + `')"></div>
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading"><a href="` + data.doc_url + `">` + data.name + `</a></h4>
                        <div class="media-description ck-content">` + data.description + `</div>
                        <ul class="list-inline list-unstyled">
                            <li><span><i class="glyphicon glyphicon-calendar"></i> ` + data.created_at + ` </span></li>
                            <li>|</li>
                            <span><i class="glyphicon glyphicon-comment"></i> ` + data.comment_count + ` nhận xét</span>
                        </ul>
                    </div>
                </div>
            </div>
            `
        }

        function onOderChange(){
            search();
        }

        function showMore(type){
            var nextPageVar = 'search_next_page_url';
            if (type === 'newest'){
                nextPageVar = 'newest_next_page_url';
            }
            else if (type === 'mostview'){
                nextPageVar = 'mostview_next_page_url';
            }

            if (window[nextPageVar] != null){
                $.ajax({
                    type: "get",
                    cache: false,
                    url: window[nextPageVar],
                    beforeSend: function(){
                        showLoading();
                    },
                    complete: function(){
                        hideLoading();
                    },
                    error: function (xhr,status,error) {

                    },
                    success: function (result,status,xhr) {
                        showResult(result, false, type)
                    }
                });
            }
        }
    </script>
@endpush
