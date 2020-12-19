@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{url('assets/css/ckeditor.css')}}">
    <style>
        .widget .panel-body { padding:0px; }
        .widget .list-group { margin-bottom: 0; }
        .widget .panel-title { display:inline }
        .widget .label-info { float: right; }
        .widget li.list-group-item {border-radius: 0;border: 0;border-top: 1px solid #ddd;}
        .widget li.list-group-item:hover { background-color: rgba(86,61,124,.1); }
        .widget .mic-info { color: #666666;font-size: 11px; }
        .widget .action { margin-top:5px; }
        .widget .comment-text { font-size: 12px; }
        .widget .btn-block { border-top-left-radius:0px;border-top-right-radius:0px; }
    </style>
@endsection

@section('content')
    <h1 class="mb-5 font-sans">{{$document->name}}</h1>

    <div class="flex items-center text-sm text-light">
        <span>{{date( 'd/m/Y', strtotime( $document->created_at ))}}</span>
        &nbsp;—&nbsp;
        @foreach($document->tags()->get() as $current_tag)
            <a href="{{url('/?tag=' . $current_tag->getTranslation('name', 'vi'))}}" class="badge" style="background-color: #17a2b8; margin: 0 1px">#{{$current_tag->getTranslation('name', 'vi')}}</a>
        @endforeach
    </div>
    @if (!empty($document->thumbnail))
    <div class="mt-5">
        <img style="width: 100%;" src="{{ url("storage/".$document->thumbnail) }}">
    </div>
    @endif
    <div class="mt-5 leading-loose flex flex-col justify-center items-center post-body font-serif ck-content">
        {!! $document->description !!}
    </div>
    <hr>
    @if (!empty($attachments))
    <div id="attachments">
        <div class="panel panel-default widget">
            <div class="panel-heading">
                <span class="glyphicon glyphicon-file" style="color: #337ab7"></span>
                <h3 class="panel-title">Tài liệu đính kèm</h3>
            </div>
            <div class="panel-body">
                <ul class="list-group">
                @foreach($attachments as $attachment)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-xs-9 col-md-10">
                                <div class="comment-text">
                                    {{ $attachment->file_name }}
                                </div>
                                <div>
                                    <div class="mic-info">
                                        {{ formatSizeUnits($attachment->size) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-3 col-md-2">
                                <div class="action text-right">
                                    @if(in_array($attachment->extension, ['pdf', 'png, jpg']))
                                    <a class="btn btn-success btn-xs" title="Xem"
                                       href="{{ url('storage/' . $attachment->file_path) }}" target="_blank">
                                        <span class="glyphicon glyphicon-eye-open"></span>
                                    </a>
                                    @endif
                                    <a class="btn btn-primary btn-xs" title="Tải xuống"
                                       href="{{ url('storage/' . $attachment->file_path) }}" download>
                                        <span class="glyphicon glyphicon-download-alt"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
    @if($document->disable_comment == 0)
    <div id="comments">
        <div class="panel panel-default widget">
            <div class="panel-heading">
                <span class="glyphicon glyphicon-comment" style="color: #337ab7"></span>
                <h3 class="panel-title">Nhận xét người đọc</h3>
            </div>
            <div class="panel-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        {!! Form::open(['route' => 'add_comment']) !!}
                        <input type="hidden" name="document_id" value="{{$document->id}}">
                        <div class="row form-group">
                            <div class="col-xs-12">
                                <label for="content">Nhận xét của bạn</label>
                                <textarea id="content" name="content" class="form-control" maxlength="255"></textarea>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Gửi</button>
                        </div>
                        {!! Form::close() !!}
                    </li>
                    @foreach($comments as $comment)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-xs-2 col-md-1">
                                <img src="{{ asset('assets/images/default-avatar.jpg') }}" class="img-circle img-responsive" alt="" /></div>
                            <div class="col-xs-10 col-md-11">
                                <div>
                                    <div class="mic-info">
                                        <a href="#">{{$comment->user}}</a> vào lúc {{$comment->date}}
                                    </div>
                                </div>
                                <div class="comment-text">
                                    {{$comment->content}}
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
{{--                <a href="#" class="btn btn-primary btn-sm btn-block" role="button">--}}
{{--                    <span class="glyphicon glyphicon-refresh"></span>--}}
{{--                    Xem thêm--}}
{{--                </a>--}}
            </div>
        </div>
    </div>
    @endif
@endsection
