<div class="col-md-8">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Thông tin tài liệu</h3>
            <div class="box-tools pull-right">
                <!-- Collapse Button -->
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <!-- Name Field -->
                <div class="form-group col-md-12 @if($errors->has('name')) has-error @endif">
                    {!! Form::label('name', 'Tên tài liệu:') !!}
                    {!! Form::text('name', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
                    @if($errors->has('name'))
                        <div class="help-block">{{ $errors->first('name') }}</div>
                    @endif
                </div>

                <!-- Description Field -->
                <div class="form-group col-md-12 @if($errors->has('description')) has-error @endif">
                    {!! Form::label('description', 'Mô tả tài liệu:') !!}
                    {!! Form::textarea('description', null, ['class' => 'form-control editor']) !!}
                    @if($errors->has('description'))
                        <div class="help-block">{{ $errors->first('description') }}</div>
                    @endif
                </div>

                <div class="form-group col-md-12 @if($errors->has('tags')) has-error @endif">
                    {!! Form::label('tags', 'Thẻ tag:') !!}
                    <select id="tag_selector" class="form-control select2-multiple" name="tags[]" multiple="multiple">
                        @if(old('tags'))
                            @foreach(old('tags') as $old_tag)
                                <option selected value="{{ $old_tag }}">{{ $old_tag }}</option>
                            @endforeach
                            @foreach($tags as $tag)
                                @if(!in_array($tag->getTranslation('name', 'vi'), old('tags')))
                                    <option value="{{$tag->getTranslation('name', 'vi')}}">{{$tag->getTranslation('name', 'vi')}}</option>
                                @endif
                            @endforeach
                        @elseif(isset($document))
                            @php $doc_tags = [] @endphp
                            @foreach($document->tags()->get() as $current_tag)
                                @php $doc_tags[] = $current_tag->getTranslation('name', 'vi') @endphp
                                <option selected value="{{ $current_tag->getTranslation('name', 'vi') }}">{{ $current_tag->getTranslation('name', 'vi') }}</option>
                            @endforeach
                            @foreach($tags as $tag)
                                @if(!in_array($tag->getTranslation('name', 'vi'), $doc_tags))
                                    <option value="{{$tag->getTranslation('name', 'vi')}}">{{$tag->getTranslation('name', 'vi')}}</option>
                                @endif
                            @endforeach
                        @else
                            @foreach($tags as $tag)
                                <option value="{{$tag->getTranslation('name', 'vi')}}">{{$tag->getTranslation('name', 'vi')}}</option>
                            @endforeach
                        @endif
                    </select>
                    @if($errors->has('tags'))
                        <div class="help-block">{{ $errors->first('tags') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">File đính kèm</h3>
            <div class="box-tools pull-right">
                <!-- Collapse Button -->
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <div id="actions" class="row">
                        <div class="col-lg-7">
                            <!-- The fileinput-button span is used to style the file input field as button -->
                            <span class="btn btn-success fileinput-button dz-clickable">
                                <i class="glyphicon glyphicon-plus"></i>
                                <span>Thêm file</span>
                            </span>
                            <button type="button" class="btn btn-primary start">
                                <i class="glyphicon glyphicon-upload"></i>
                                <span>Tải lên</span>
                            </button>
                            <button type="button" class="btn btn-danger cancel">
                                <i class="glyphicon glyphicon-trash"></i>
                                <span>Xóa tất cả</span>
                            </button>
                        </div>
                        <div class="col-lg-5" style="margin-top: 10px;">
                            <!-- The global file processing state -->
                            <span class="fileupload-process">
                                <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" style="opacity: 0;">
                                    <div class="progress-bar progress-bar-success" style="width: 100%;" data-dz-uploadprogress=""></div>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table table-striped" class="files" id="previews">
                        <div id="template" class="file-row row">
                            <!-- This is used as the file preview template -->
                            <div class="col-sm-7">
                                <p class="name text-wrap" style="max-width: 70%" data-dz-name></p>
                                <p class="size" data-dz-size></p>
                                <strong class="error text-danger dz-error-message"></strong>
                            </div>
                            <div class="col-sm-5">
                                <button type="button" class="btn btn-primary start" hidden>
                                    <i class="glyphicon glyphicon-upload"></i>
                                    <span>Start</span>
                                </button>
                                <button type="button"  data-dz-remove class="btn btn-warning cancel">
                                    <i class="glyphicon glyphicon-ban-circle"></i>
                                    <span>Hủy</span>
                                </button>
                                <button type="button"  data-dz-remove class="btn btn-danger delete">
                                    <i class="glyphicon glyphicon-trash"></i>
                                    <span>Xóa</span>
                                </button>
                                <div style="margin-top: 5px" class="progress progress-striped active"
                                     role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                    <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div hidden id="uploaded_file"></div>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Thiết lập tài liệu</h3>
            <div class="box-tools pull-right">
                <!-- Collapse Button -->
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="form-group col-md-12 @if($errors->has('thumbnail')) has-error @endif">
                {!! Form::label('thumbnail', 'Ảnh đại diện cho tài liệu:') !!}
                <div class="upload-thumbnail @if(isset($document) && !empty($document->thumbnail)) ready @endif">
                    <input id="thumbnail" type="file" name="thumbnail" accept="image/*" data-role="none" hidden="">
                    <input id="old_thumbnail" type="hidden" name="old_thumbnail" value="1">
                    <div>
                        <div class="thumbnail-msg">Click để chọn ảnh</div>
                        <div id="thumbnail-display">
                            <img id="thumbnail_preview_container"
                                 src="@if(isset($document) && !empty($document->thumbnail)){{url("storage/".$document->thumbnail)}}@else # @endif"
                                 name="image" alt="preview image" style="width: 100%;">
                        </div>
                        <div class="buttons text-center" style="margin-top: 5px">
                            <button id="reset" type="button" class="reset btn btn-danger">Xóa ảnh</button>
                        </div>
                    </div>
                </div>

                @if($errors->has('thumbnail'))
                    <div class="help-block">{{ $errors->first('thumbnail') }}</div>
                @endif
            </div>
            <div class="form-group col-md-12 @if($errors->has('privacy')) has-error @endif">
                {!! Form::label('privacy', 'Cấp độ bảo mật:') !!}
                <select class="form-control select2 select2-hidden-accessible" name="privacy">
                    <option></option>
                    @foreach($privacies as $privacy)
                        <option @if((old('privacy') && old('privacy') == $privacy->id) || (isset($document) && $document->privacy == $privacy->id)) selected @endif value="{{$privacy->id}}">{{$privacy->name}}</option>
                    @endforeach
                </select>
                @if($errors->has('privacy'))
                    <div class="help-block">{{ $errors->first('privacy') }}</div>
                @endif
            </div>
            <div class="form-group col-md-12 @if($errors->has('status')) has-error @endif">
                {!! Form::label('status', 'Trạng thái tài liệu:') !!}
                <select class="form-control select2 select2-hidden-accessible" name="status">
                    <option></option>
                @foreach(\App\Models\Admin\Document::STATUS_LABEL as $status => $status_label)
                    <option @if((old('status') && old('status') == $status) || (isset($document) && $document->status == $status)) selected @endif value="{{ $status }}">{{ $status_label }}</option>
                @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="help-block">{{ $errors->first('status') }}</div>
                @endif
            </div>
            <div class="form-group col-md-12 m-t-20 @if($errors->has('disable_comment')) has-error @endif">
                {!! Form::label('disable_comment', 'Chặn tính năng nhận xét:', ['class' => 'pull-left']) !!}
                <span class="pull-right">
                    <label class="switch">
                    {!! Form::checkbox('disable_comment', 1, null) !!}
                    <span class="slider round"></span>
                </label>
                </span>
                @if($errors->has('disable_comment'))
                    <div class="help-block">{{ $errors->first('disable_comment') }}</div>
                @endif
            </div>

        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Lưu</h3>
            <div class="box-tools pull-right">
                <!-- Collapse Button -->
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="form-group col-md-12 @if($errors->has('save_type')) has-error @endif">
                {!! Form::label('save_type', 'Lưu dưới dạng:') !!}
                <select class="form-control select2 select2-hidden-accessible" name="save_type">
                    <option></option>
                    @foreach(\App\Models\Admin\Document::SAVE_TYPE_LABEL as $save_type => $save_type_label)
                        <option @if((old('save_type') && old('save_type') == $save_type) || (isset($document) && $document->draft == $save_type)) selected @endif value="{{ $save_type }}">{{ $save_type_label }}</option>
                    @endforeach
                </select>
                @if($errors->has('save_type'))
                    <div class="help-block">{{ $errors->first('save_type') }}</div>
                @endif
            </div>

            <div class="form-group col-md-12 text-center">
                @if(old('temp_id'))
                    <input type="hidden" name="temp_id" value="{{ old('temp_id') }}">
                @elseif(isset($temp_id))
                    <input type="hidden" name="temp_id" value="{{ $temp_id }}">
                @endif
                {!! Form::submit('Lưu', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('admin.documents.index') }}" class="btn btn-default">Quay lại</a>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script src="{{ url('assets/dropzone/dropzone.min.js') }}"></script>
    <script>
        // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
        var previewNode = document.querySelector("#template");
        previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
            url: "{{ route('api.upload') }}", // Set the url
            // thumbnailWidth: 80,
            // thumbnailHeight: 80,
            parallelUploads: 20,
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: ".fileinput-button", // Define the element that should be used as click trigger to select files.

            maxFilesize: 5,
            createImageThumbnails:false,
            acceptedFiles:'.mp3, .mp4, .png, .jpg, .gif, .doc, .docx, .pdf, .xls, .xlsx, .ppt, .pptx, .html, .htm, .xlm, .rtf',
            init: function (){
                @if(old('files'))
                @foreach(old('files') as $uploaded_file)
                @php $file_info = json_decode($uploaded_file) @endphp
                var mockFile = {
                    name: "{{ property_exists($file_info, 'filename') ? $file_info->filename : $file_info->name}}",
                    id: "{{$file_info->id}}",
                    size: "{{property_exists($file_info, 'total') ? $file_info->total : $file_info->size}}",
                    accepted: true,
                    upload: {!! $uploaded_file !!}
                }
                this.files.push(mockFile);
                this.emit("addedfile", mockFile);
                this.emit("complete", mockFile);
                this.emit("success", mockFile);
                $('<input>').attr({
                    type: 'hidden',
                    name: 'files[]',
                    value: JSON.stringify(mockFile),
                    'data-uuid': mockFile.upload.uuid
                }).appendTo('#uploaded_file');
                @endforeach
                @elseif(isset($document) && isset($document_files))
                @foreach($document_files as $uploaded_file)
                var mockFile = {
                    name: "{{$uploaded_file->file_name}}",
                    id: "{{$uploaded_file->id}}",
                    size: "{{$uploaded_file->size}}",
                    accepted: true,
                    upload: {uuid: uuidv4()}
                }
                this.files.push(mockFile);
                this.emit("addedfile", mockFile);
                this.emit("complete", mockFile);
                this.emit("success", mockFile);
                $('<input>').attr({
                    type: 'hidden',
                    name: 'files[]',
                    value: JSON.stringify(mockFile),
                    'data-uuid': mockFile.upload.uuid
                }).appendTo('#uploaded_file');
                @endforeach
                @endif
            }
        });

        myDropzone.on("addedfile", function(file) {
            // Hookup the start button
            file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file); };
        });

        myDropzone.on("removedfile", function(file) {
            // Hookup the start button
            console.log(file)
            $('#uploaded_file').find('input[data-uuid="' + file.upload.uuid + '"]').remove();
        });

        // Update the total progress bar
        myDropzone.on("totaluploadprogress", function(progress) {
            document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
        });

        myDropzone.on("sending", function(file, xhr, formData) {
            // Show the total progress bar when upload starts
            document.querySelector("#total-progress").style.opacity = "1";
            // And disable the start button
            file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");

            formData.append("user_id", {{ \Illuminate\Support\Facades\Auth::guard('admins')->user()->id }});
            formData.append("temp_id", "{{ old('temp_id') ?? $temp_id }}");
        });

        myDropzone.on('error', function(file, response) {
            let error_msg = "Có lỗi xảy ra. Vui lòng thử lại";
            if (typeof response == "string"){
                if (response === "You can't upload files of this type."){
                    error_msg = 'Chỉ hỗ trợ các file có định dạng: .mp3, .mp4, .png, .jpg, .gif, .doc, .docx, .pdf, .xls, .xlsx, .ppt, .pptx, .html, .htm, .xlm, .rtf';
                }
                else if(response.includes("File is too big")){
                    error_msg = "File quá lớn! Dung lượng tối đa là 5MB";
                }
                else{
                    error_msg = response;
                }
            }
            if (response.hasOwnProperty('message')){
                error_msg = typeof(response.message) == "string" ? response.message : JSON.stringify(response.message)
            }
            $(file.previewElement).find('.dz-error-message').text(error_msg);
        });

        myDropzone.on('success', function(file, response) {
            console.log(file)
            let error_msg = "Có lỗi xảy ra. Vui lòng thử lại";
            if (response.hasOwnProperty('success') && response.success === true && response.hasOwnProperty('id')){
                $('<input>').attr({
                    type: 'hidden',
                    name: 'files[]',
                    value: JSON.stringify({...file.upload, id: response.id}),
                    'data-uuid': file.upload.uuid
                }).appendTo('#uploaded_file');
                return;
            }
            else if (response.hasOwnProperty('message')){
                error_msg = typeof(response.message) == "string" ? response.message : JSON.stringify(response.message)
            }
            $(file.previewElement).find('.dz-error-message').text(error_msg);
        });

        // Hide the total progress bar when nothing's uploading anymore
        myDropzone.on("queuecomplete", function(progress) {
            document.querySelector("#total-progress").style.opacity = "0";
        });

        // Setup the buttons for all transfers
        // The "add files" button doesn't need to be setup because the config
        // `clickable` has already been specified.
        document.querySelector("#actions .start").onclick = function() {
            myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
        };
        document.querySelector("#actions .cancel").onclick = function() {
            if (confirm("Chắc chắn xóa")){
                myDropzone.removeAllFiles(true);
                $('#uploaded_file').empty();
            }
        };
        function uuidv4() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }
    </script>
    <script src="https://cdn.ckeditor.com/ckeditor5/23.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('.editor'))
            .then(editor => {
                // console.log(editor);
            })
            .catch(error => {
                // console.error(error);
            });
    </script>
    <script>
        $('#tag_selector').select2({
            tags: true
        });
        $('select[name="privacy"]').select2({
            placeholder: "--- Chọn cấp độ bảo mật ---"
        })
        $('select[name="status"]').select2({
            placeholder: "--- Chọn trạng thái ---"
        })
        $('select[name="save_type"]').select2({
            placeholder: "--- Chọn dạng lưu ---"
        })

        // UPLOAD THUMBNAIL
        $(document).on('click', '.thumbnail-msg', function () {
            $("#thumbnail").trigger("click");
        });

        $('#reset').on("click", function () {
            $('#thumbnail-display').removeAttr('hidden');
            $('#reset').attr('hidden');
            $('.upload-thumbnail').removeClass('ready result');
            $('#thumbnail_preview_container').attr('src', '#');
            $('#thumbnail').val("");
            $('#old_thumbnail').remove();
        });

        function readFile(input) {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('.upload-thumbnail').addClass('ready');
                $('#thumbnail_preview_container').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }

        $('#thumbnail').on('change', function () {
            readFile(this);
        });

        $('form').submit(function (){
            console.log()
            if (myDropzone.getFilesWithStatus(Dropzone.ADDED).length > 0 || myDropzone.getFilesWithStatus(Dropzone.PROCESSING).length > 0){
                alert("Vui lòng hoàn thành tải file trước khi lưu")
                return false;
            }
            return true;
        })

    </script>
@endpush
