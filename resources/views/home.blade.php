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
{{--            <div class="row" style="margin-top: 15px">--}}
{{--                <div class="col-xs-12">--}}
{{--                    <div class="panel-group" id="accordion">--}}
{{--                        <div class="">--}}
{{--                            <div class="">--}}
{{--                                <div class="">--}}
{{--                                    <a data-toggle="collapse"--}}
{{--                                       data-parent="#accordion"--}}
{{--                                       href="#extra-search"--}}
{{--                                       style="color: #367fa9">--}}
{{--                                        Tìm kiếm nâng cao--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div id="extra-search" class="panel-collapse collapse">--}}
{{--                                <div class="panel-body">--}}
{{--                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit,--}}
{{--                                    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad--}}
{{--                                    minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea--}}
{{--                                    commodo consequat.--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
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
        var next_page_url = null;
        searchDocument({
            type: 'newest'
        }, true);

        $('#search_form').submit(function (event){
            event.preventDefault();
            var formdata = $(this).serializeArray();
            var data = {};
            $(formdata).each(function(index, obj){
                data[obj.name] = obj.value;
            });
            searchDocument(data, true)
        });

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
            if (data.hasOwnProperty('data') && data.data.length > 0){
                for (let i in data.data){
                    $('#documents-container').append(documentHtml(data.data[i]))
                }
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
                '        <h2 class="font-sans leading-normal block">' + data.name + '</h2>\n' +
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
