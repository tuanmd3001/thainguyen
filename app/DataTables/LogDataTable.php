<?php

namespace App\DataTables;

use App\DataTables\ExportHandler\BaseExportHandler;
use App\Models\Admin\Document;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class LogDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        $dataTable->filter(function ($query){
            $req = $this->request()->all();
            if (isset($req['search']) && isset($req['search']['value']) && $req['search']['value'] != ""){
                $query->whereIn('causer_id', function ($q) use ($req) {
                    $q->select('id')->from('users')->where('name', 'LIKE', '%'. $req['search']['value'] . '%');
                });
            }
            $query->where('log_name', 'web')->orderBy('id', 'desc');
        }, true);

        return $dataTable
            ->addIndexColumn()
            ->editColumn('username', function ($temp){
                return $temp->causer->name;
            })
            ->editColumn('description', function ($temp){
                if ($temp->description == 'search'){
                    return "Tìm kiếm";
                }
                elseif ($temp->description == 'view'){
                    return "Xem tài liệu";
                }
                elseif ($temp->description == 'comment'){
                    return "Nhận xét tài liệu";
                }
                elseif ($temp->description == 'download'){
                    return "Tải file";
                }
                else {
                    return "";
                }
            })
            ->editColumn('detail', function ($temp) {
                if ($temp->description == 'search'){
                    $display = [];
                    $filters = $temp->getExtraProperty('filters');
                    if (isset($filters['txtSearch']) && trim($filters['txtSearch']) != ""){
                        $display[] = "Từ khóa: <b>" . $filters['txtSearch'] . "</b>";
                        if (isset($filters['searchTitle']) && $filters['searchTitle'] == 'on'){
                            $display[] = " - Tìm kiếm trong tiêu đề";
                        }
                        if (isset($filters['searchDesc']) && $filters['searchDesc'] == 'on'){
                            $display[] = " - Tìm kiếm trong nội dung";
                        }
                        if (isset($filters['searchFile']) && $filters['searchFile'] == 'on'){
                            $display[] = " - Tìm kiếm trong file đính kèm";
                        }
                        if (isset($filters['searchComment']) && $filters['searchComment'] == 'on'){
                            $display[] = " - Tìm kiếm trong file comment";
                        }
                    }
                    if (isset($filters['start']) && trim($filters['start']) != ""){
                        $display[] = "Từ ngày: " . $filters['start'];
                    }
                    if (isset($filters['end']) && trim($filters['end']) != ""){
                        $display[] = "Đến ngày: " . $filters['end'];
                    }
                    if (isset($filters['tags']) && count($filters['tags'])){
                        $display[] = "Thẻ tag: " . implode(', ', $filters['tags']);
                    }
                    return implode("<br>", $display);
                }
                elseif ($temp->description == 'view'){
                    $doc = $temp->subject;
                    if ($doc){
                        return "Tài liệu: <a href='".route('view_document', $doc->slug)."'>".$doc->name."</a>";
                    }
                    return "";
                }
                elseif ($temp->description == 'comment'){
                    $comment = $temp->subject;
                    $doc = Document::find($temp->subject->document_id);
                    $html = "Nhận xét: <b>" . $comment->content . "</b>";
                    if ($doc){
                        $html .= "<br>Tài liệu: <a href='".route('view_document', $doc->slug)."'>".$doc->name."</a>";
                    }
                    return $html;
                }
                elseif ($temp->description == 'download'){
                    $file = $temp->subject;
                    $doc = Document::find($temp->subject->document_id);
                    $html = "File: <b>" . $file->file_name . '</b>';
                    if ($doc){
                        $html .= "<br>Tài liệu: <a href='".route('view_document', $doc->slug)."'>".$doc->name."</a>";
                    }
                    return $html;
                }
                else {
                    return "";
                }

            })
            ->editColumn('created_at', function ($temp){
                return date( 'd/m/Y H:i:s', strtotime( $temp->created_at ));
            })
            ->rawColumns(['detail']);
//            ->addColumn('action', 'admin.logs.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\RoleBak $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Activity $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
//            ->addAction(['width' => '120px', 'printable' => false, 'title' => 'Thao tác', 'className' => 'text-center'])
            ->parameters([
                'responsive'=> true,
                'dom' => '<"row"<"col-xs-12"f>><"row"<"col-xs-8 p-t-5"l><"col-xs-4 text-right hidden-print"B>>" +
                    "<"row m-t-10"<"col-sm-12"tr>>" +
                    "<"row"<"col-sm-6"i><"col-sm-6 hidden-print"p>>',
                'language' => [
                    "emptyTable" => "Không có bản ghi nào",
                    "info" => "Hiển thị bản ghi _START_ - _END_ trên tổng _TOTAL_ bản ghi",
                    "infoEmpty" => "Hiển thị 0 bản ghi",
                    "infoFiltered" => "",
                    "infoPostFix" => "",
                    "thousands" => ",",
                    "lengthMenu" => "Hiển thị _MENU_ bản ghi",
                    "loadingRecords" => "Đang tải...",
                    "processing" => 'Đang xử lý...',
                    "search" => "Tìm kiếm: ",
                    "zeroRecords" => "Không tìm thấy bản ghi nào",
                    "paginate" => [
                        "first" => "«",
                        "last" => "»",
                        "next" => ">",
                        "previous" => "<"
                    ],
                    "aria" => [
                        "sortAscending" => ": activate to sort column ascending",
                        "sortDescending" => ": activate to sort column descending"],
                    "buttons" => [
                        "copy" => "Sao chép",
                        "excel" => "Xuất Excel",
                        "csv" => "CSV",
                        "pdf" => "PDF",
                        "print" => "In",
                        "colvis" => "Chọn cột hiển thị"
                    ]
                ],
                'autoWidth' => false,
                "ordering" => false,
                'stateSave' => false,
                'order'     => [[0, 'desc']],
                'buttons'   => [
                    ['extend' => 'excel']
                ],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'STT','searchable' => false, 'className' => 'text-center'],
            ['data' => 'username', 'name' => 'username', 'title' => 'Người đọc','searchable' => false],
            ['data' => 'description', 'name' => 'description', 'title' => 'Hành động', 'className' => 'text-center','searchable' => false],
            ['data' => 'detail', 'name' => 'detail', 'title' => 'Nội dung','searchable' => false],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Thời gian', 'className' => 'text-center','searchable' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'roles_datatable_' . time();
    }
    protected $exportClass = BaseExportHandler::class;
}
