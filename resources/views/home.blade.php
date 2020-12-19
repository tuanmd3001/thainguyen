@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{url('assets/css/spinner.css')}}">
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
                                                <span class="input-group-addon">Từ ngày</span>
                                                <input type="text" class="input-sm form-control" name="start" />
                                                <span class="input-group-addon">Đến ngày</span>
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
    <div id="documents-container">
    </div>
    <div id="showMoreBtn" class="uppercase flex items-center justify-center flex-1 font-sans">
        <a href="javascript:void(0)" onclick="showMore()" rel="next" class="block no-underline text-light hover:text-black px-5">
            Hiển thị thêm
        </a>
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
        var next_page_url = null;
        $( document ).ready(function (){
            var urlParams = getUrlParameter()
            if (Object.keys(urlParams).length > 0){
                if (urlParams['tag']){
                    if ($('#tag_selector').find("option[value='" + urlParams['tag'] + "']").length) {
                        $('#tag_selector').val(urlParams['tag']).trigger('change');

                    }
                }
                search();
            } else {
                searchDocument({
                    type: 'newest'
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
            });
            if (validateSearch(data)){
                searchDocument(data, true)
            }
        }

        function validateSearch(data){
            if (data['start'].trim() || data['end'].trim()){
                return true;
            }
            return !!(data['tags'].length > 0 || (data['txtSearch'].trim() && (data['searchTitle'] || data['searchDesc'] || data['searchFile'] || data['searchComment'])));

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
                    showResult(result, refresh)
                }
            });
        }

        function showLoading() {
            $('#spinner-container').show();
        }

        function hideLoading() {
            $('#spinner-container').hide();
        }

        function showResult(data, refresh = false) {
            if (refresh){
                $('#documents-container').empty();
            }
            if (data.hasOwnProperty('title')){
                $('#documents-container').append('<h1 class="mb-5">' + data.title + '</h1>')
            }
            if (data.hasOwnProperty('data') && data.data.length > 0){
                for (let i in data.data){
                    $('#documents-container').append(documentHtml(data.data[i]))
                }
            }
            else {
                $('#documents-container').append('<span>Không tìm thấy tài liệu</span>')
            }

            $('#showMoreBtn').hide();
            if (data.hasOwnProperty("next_page_url")){
                next_page_url = data.next_page_url;
                if (data.next_page_url !== null){
                    $('#showMoreBtn').show();
                }
            }
        }
        function documentHtml(data){
            return '<a class="no-underline transition block border border-lighter w-full mb-10 p-5 rounded post-card" href="' + data.doc_url + '">\n' +
                '<div class="block h-post-card-image bg-cover bg-center bg-no-repeat w-full h-48" style="background-image: url(\''+ data.thumbnail +'\')"></div>\n' +
                '<div class="flex flex-col justify-between flex-1">' +
                '    <div>\n' +
                '        <h3 class="font-sans leading-normal block">' + data.name + '</h3>\n' +
                // '        <div class="leading-normal mb-6 font-serif leading-loose">' + data.description + '</div>\n' +
                '    </div>\n' +
                '    <div class="flex items-center text-sm text-light">\n' +
                '        <span class="">' + data.created_at + '</span>\n' +
                '    </div>\n' +
                '</div>\n' +
                '</a>'
        }

        function showMore(){
            if (next_page_url != null){
                $.ajax({
                    type: "get",
                    cache: false,
                    url: next_page_url,
                    beforeSend: function(){
                        showLoading();
                    },
                    complete: function(){
                        hideLoading();
                    },
                    error: function (xhr,status,error) {

                    },
                    success: function (result,status,xhr) {
                        showResult(result, false)
                    }
                });
            }
        }
    </script>
@endpush
