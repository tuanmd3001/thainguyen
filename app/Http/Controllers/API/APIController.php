<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\AppBaseController;
use App\Models\Admin\Attachment;
use App\Models\Admin\Comment;
use App\Models\Admin\Document;
use App\Models\Constants;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class APIController extends AppBaseController
{
    public function uploadFile(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required',
                'file' => 'required|mimes:' . implode(',', Constants::ALLOW_UPLOAD_MIME_TYPE) . '|max:5120',
            ],
            $messages = [
                'user_id.required' => "Thiếu tham số. Vui lòng tải lại trang",
                'required.required' => "Không tìm thấy file",
                'file.mimes' => 'Chỉ hỗ trợ các file có định dạng: .mp3, .mp4, .png, .jpg, .gif, .doc, .docx, .pdf, .xls, .xlsx, .ppt, .pptx, .html, .htm, .xlm, .rtf',
                'file.max' => 'File không được có dung lượng quá 5MB'
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
            "size" => $file->getSize(),
            "extension" => strtolower($file->getClientOriginalExtension()),
            "document_id" => $request->has('document_id') ? $request['document_id'] : null,
            "temp_id" => $request->has('temp_id') ? $request['temp_id'] : null,
            "upload_by" => $request['user_id']
        ]);


        return response()->json([
            "success" => true,
            "id" => $attachment->id,
            "message" => "File successfully uploaded"
        ]);
    }


    public function search(Request $request)
    {
        return response()->json($this->getDocuments($request->all()));
    }

    private function getDocuments($filters){
        $itemsPaginated = null;
        if (isset($filters['type'])){
            if ($filters['type'] == 'newest') {
                $itemsPaginated = $this->getNewest();
            }
        }
        else {
            $itemsPaginated = Document::where('draft', Document::SAVE_TYPE_PUBLIC)
                ->where('status', Document::STATUS_EXPLOIT);
            if (isset($filters['txtSearch']) && trim($filters['txtSearch']) != ""){
                $where = [];
                if (isset($filters['searchTitle']) && $filters['searchTitle'] == 'on'){
                    $where[] = 'name LIKE "%' . trim($filters['txtSearch']) . '%"';
                }
                if (isset($filters['searchDesc']) && $filters['searchDesc'] == 'on'){
                    $where[] = 'description_text LIKE "%' . trim($filters['txtSearch']) . '%"';
                }
                if (isset($filters['searchFile']) && $filters['searchFile'] == 'on'){
                    $idsByFiles = Attachment::where('content', 'LIKE', '%' . trim($filters['txtSearch']) . '%')
                        ->pluck('document_id')->toArray();
                    $where[] = 'id IN (' . ($idsByFiles ? implode(', ', $idsByFiles): 'NULL') . ')';
                }
                if (isset($filters['searchComment']) && $filters['searchComment'] == 'on'){
                    $idsByComments = Comment::where('content', 'LIKE', '%' . trim($filters['txtSearch']) . '%')
                        ->pluck('document_id')->toArray();
                    $where[] = 'id IN (' . ($idsByComments ? implode(', ', $idsByComments) : 'NULL') . ')';
                }
                if (count($where)){
                    $itemsPaginated = $itemsPaginated->whereRaw('( ' . implode(' OR ', $where) . ' )');
                }
            }
            if (isset($filters['start']) && trim($filters['start']) != ""){
                $start = \DateTime::createFromFormat('d/m/Y', $filters['start']);
                $itemsPaginated = $itemsPaginated->where('created_at', '>=', $start->format('Y-m-d').' 00:00:00');
            }
            if (isset($filters['end']) && trim($filters['end']) != ""){
                $end = \DateTime::createFromFormat('d/m/Y', $filters['end']);
                $itemsPaginated = $itemsPaginated->where('created_at', '<=', $end->format('Y-m-d').' 23:59:59');
            }
            $itemsPaginated = $itemsPaginated->orderBy('created_at', 'desc')->orderBy('id', 'desc')->paginate(self::PER_PAGE);
        }

        if (isset($filters['_'])){
            unset($filters['_']);
        }
        return $this->formatSearchResult($filters, $itemsPaginated);
    }

    private const PER_PAGE = 10;

    private function getNewest()
    {
        return Document::where('draft', Document::SAVE_TYPE_PUBLIC)
            ->where('status', Document::STATUS_EXPLOIT)
            ->orderBy('created_at', 'desc')->orderBy('id', 'desc')
            ->paginate(self::PER_PAGE);
    }

    private function formatSearchResult($filters, $itemsPaginated)
    {
        if ($itemsPaginated->count()){
            $itemsTransformed = $itemsPaginated
                ->getCollection()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'description' => $item->description,
                        'thumbnail' => !empty($item->thumbnail) ? url("storage/".$item->thumbnail) : url('assets/images/noimage.jpg'),
                        'created_at' => date( 'd/m/Y', strtotime( $item->created_at )),
                        'doc_url' => route('view_document', $item->slug)
                    ];
                })
                ->toArray();

            return new class(
                $itemsTransformed,
                $itemsPaginated->total(),
                $itemsPaginated->perPage(),
                $itemsPaginated->currentPage(), [
                    'path' => \Request::url(),
                    'query' => $filters,
                    'title' => isset($filters['type']) && $filters['type'] == 'newest' ? "Bài viết mới" : 'Kết quả tìm kiếm'
                ]
            ) extends LengthAwarePaginator {
                public function toArray()
                {
                    $data = parent::toArray();
                    // place whatever you want to send here
                    $data['title'] = $this->title;
                    return $data;
                }
            };
        }
        else {
            return new class(
                [],
                1,
                self::PER_PAGE,
                1, [
                    'path' => \Request::url(),
                    'query' => $filters,
                    'title' => isset($filters['type']) && $filters['type'] == 'newest' ? "Bài viết mới" : 'Kết quả tìm kiếm'
                ]
            ) extends LengthAwarePaginator {
                public function toArray()
                {
                    $data = parent::toArray();
                    // place whatever you want to send here
                    $data['title'] = $this->title;
                    return $data;
                }
            };
        }
    }

}
