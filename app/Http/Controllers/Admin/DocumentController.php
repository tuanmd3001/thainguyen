<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\DocumentDataTable;
use App\Http\Requests\CreateDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Admin\Attachment;
use App\Models\Admin\Document;
use App\Models\Admin\Privacy;
use App\Repositories\DocumentRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use Response;
use Spatie\Tags\Tag;

class DocumentController extends AppBaseController
{
    /** @var  DocumentRepository */
    private $documentRepository;

    public function __construct(DocumentRepository $documentRepo)
    {
        $this->documentRepository = $documentRepo;
    }

    /**
     * Display a listing of the Document.
     *
     * @param DocumentDataTable $documentDataTable
     * @return Response
     */
    public function index(DocumentDataTable $documentDataTable)
    {
        return $documentDataTable->render('admin.documents.index');
    }

    /**
     * Show the form for creating a new Document.
     *
     * @return Response
     */
    public function create()
    {
        $temp_id = "temp_" . Uuid::uuid4();
        $tags = Tag::all();
        $privacies = Privacy::all();
        return view('admin.documents.create', compact('tags', 'temp_id', 'privacies'));
    }

    /**
     * Store a newly created Document in storage.
     *
     * @param CreateDocumentRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->storeAs(
                'thumbnails', Uuid::uuid4() . '.' . $request->file('thumbnail')->getClientOriginalExtension(),
                ['disk' => 'public']
            );
            $input['thumbnail'] = $path;
        }

        $input['disable_comment'] = (isset($input['disable_comment']) && $input['disable_comment']) == 1 ? 1 : 0;
        $input['draft'] = $input['save_type'] == Document::SAVE_TYPE_DRAFT ? 1 : 0;
        $input['description_text'] = preg_replace('/\s+/', ' ', strip_tags(str_replace('<', ' <', $input['description'])));
        $input['slug'] = Uuid::uuid4();
        $document = $this->documentRepository->create($input);
        activity('admin')
            ->causedBy(Auth::guard('admins')->user())
            ->performedOn($document)
            ->withProperties(['data' => $document->attributesToArray()])
            ->log('created');

        if (isset($input['tags']) && count($input['tags'])){
            foreach ($input['tags'] as $tag){
                $document->attachTag(Tag::findOrCreate($tag));
            }
        }

        if (isset($input['files']) && count($input['files'])){
            foreach ($input['files'] as $upload_file){
                $file_info = json_decode($upload_file);
                Attachment::where("temp_id", $input['temp_id'])->where('id', $file_info->id)->update([
                    'document_id' => $document->id,
                    'temp_id' => null,
                    'is_draft' => 0,
                    'downloadable' => $file_info->downloadable,
                    'mobile' => $file_info->mobile
                ]);
            }
        }
        Attachment::where('temp_id', $input['temp_id'])->delete();

        Flash::success('Document saved successfully.');

        return redirect(route('admin.documents.index'));
    }

    /**
     * Display the specified Document.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $document = $this->documentRepository->find($id);

        if (empty($document)) {
            Flash::error('Document not found');

            return redirect(route('admin.documents.index'));
        }

        return view('admin.documents.show')->with('document', $document);
    }

    /**
     * Show the form for editing the specified Document.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $document = $this->documentRepository->find($id);

        if (empty($document)) {
            Flash::error('Document not found');

            return redirect(route('admin.documents.index'));
        }
        $temp_id = "temp_" . Uuid::uuid4();
        $tags = Tag::all();
        $document_files = Attachment::where("document_id", $id)->get();
        $privacies = Privacy::all();
        return view('admin.documents.edit', compact('document', 'temp_id', 'tags', 'document_files', 'privacies'));
    }

    /**
     * Update the specified Document in storage.
     *
     * @param  int              $id
     * @param UpdateDocumentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentRequest $request)
    {
        $document = $this->documentRepository->find($id);
        if (empty($document)) {
            Flash::error('Document not found');

            return redirect(route('admin.documents.index'));
        }
        $old_data = $document->attributesToArray();
        $input = $request->all();
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->storeAs(
                'thumbnails', Uuid::uuid4() . '.' . $request->file('thumbnail')->getClientOriginalExtension(),
                ['disk' => 'public']
            );
            $input['thumbnail'] = $path;
        }
        elseif (!isset($input['old_thumbnail'])){
            $input['thumbnail'] = null;
        }

        $input['disable_comment'] = (isset($input['disable_comment']) && $input['disable_comment']) == 1 ? 1 : 0;
        $input['draft'] = $input['save_type'] == Document::SAVE_TYPE_DRAFT ? 1 : 0;
        $input['description_text'] = preg_replace('/\s+/', ' ', strip_tags(str_replace('<', ' <', $input['description'])));

        $document = $this->documentRepository->update($input, $id);
        activity('admin')
            ->causedBy(Auth::guard('admins')->user())
            ->performedOn($document)
            ->withProperties([
                'old_data' => $old_data,
                'new_data' => $document->attributesToArray()
            ])
            ->log('updated');

        if (isset($input['tags']) && count($input['tags'])){
            $document->syncTags([]);
            foreach ($input['tags'] as $tag){
                $document->attachTag(Tag::findOrCreate($tag));
            }
        }

        $keep_file = [];
        if (isset($input['files']) && count($input['files'])){
            foreach ($input['files'] as $upload_file){
                $file_info = json_decode($upload_file);
                if (property_exists($file_info, "id")){
                    Attachment::where('id', $file_info->id)->update([
                        'document_id' => $document->id,
                        'temp_id' => null,
                        'is_draft' => 0,
                        'downloadable' => $file_info->downloadable,
                        'mobile' => $file_info->mobile
                    ]);
                    $keep_file[] = $file_info->id;
                }
            }
        }
        Attachment::where('temp_id', $input['temp_id'])->delete();
        Attachment::where('document_id', $document->id)->whereNotIn("id", $keep_file)->delete();

        Flash::success('Document updated successfully.');

        return redirect(route('admin.documents.index'));
    }

    /**
     * Remove the specified Document from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $document = $this->documentRepository->find($id);

        if (empty($document)) {
            Flash::error('Document not found');

            return redirect(route('admin.documents.index'));
        }
        $old_data = $document->attributesToArray();
        $this->documentRepository->delete($id);
        activity('admin')
            ->causedBy(Auth::guard('admins')->user())
            ->performedOn($document)
            ->withProperties([
                'data' => $old_data
            ])
            ->log('deleted');

        Flash::success('Document deleted successfully.');

        return redirect(route('admin.documents.index'));
    }
}
