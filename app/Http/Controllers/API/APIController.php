<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\AppBaseController;
use App\Models\Admin\Attachment;
use App\Models\Constants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class APIController extends AppBaseController
{
    public function uploadFile(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required',
                'file' => 'required|mimes:'. implode(',',Constants::ALLOW_UPLOAD_MIME_TYPE) .'|max:5120',
            ],
            $messages = [
                'user_id.required' => "Thiếu tham số. Vui lòng tải lại trang",
                'required.required' => "Không tìm thấy file",
                'file.mimes' => 'Chỉ hỗ trợ các file có định dạng: .mp3, .mp4, .png, .jpg, .gif, .doc, .docx, .pdf, .xls, .xlsx, .ppt, .pptx, .html, .htm, .xlm, .rtf',
                'file.max'   => 'File không được có dung lượng quá 5MB'
            ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 401);
        }
        $file = $request->file('file');
        $store_name = Uuid::uuid4();
        $path = $file->storeAs(
            'attachments', $store_name . '.' . $file->getClientOriginalExtension(),
            ['disk' => 'public']
        );

        $attachment = Attachment::create([
            "file_name" => $file->getClientOriginalName(),
            "store_name" => $store_name,
            "file_path" => $path,
            "extension" => $file->getClientOriginalExtension(),
            "document_id" => $request->has('document_id') ? $request['document_id'] : null,
            "temp_id" => $request->has('temp_id') ? $request['temp_id'] : null,
            "upload_by" => $request['user_id']
        ]);


        return response()->json([
            "success" => true,
            "file_name" => $attachment->store_name,
            "message" => "File successfully uploaded"
        ]);
    }
}
