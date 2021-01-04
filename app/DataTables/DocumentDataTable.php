<?php

namespace App\DataTables;

use App\Models\Admin\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class DocumentDataTable extends DataTable
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
            $query->select('*',
                DB::raw('(SELECT COUNT(1) from activity_log WHERE log_name = "web" AND description = "view" AND subject_id = documents.id) as view'),
                DB::raw('(SELECT count(1) FROM activity_log INNER JOIN attachments on activity_log.subject_id = attachments.id  WHERE activity_log.log_name = "web" AND activity_log.description = "download" AND documents.id = attachments.document_id) as download'));
            $start = $end = null;
            if ($this->request()->has('daterange')) {
                if (!empty(request('daterange')['start'])) {
                    $start = request('daterange')['start'];
                }
                if (!empty(request('daterange')['end'])) {
                    $end = request('daterange')['end'];
                }
            }
            if ($start && $end) {
                $sql = 'created_at >= "' . $start . '" AND created_at <= "' . $end . ' 23:59:59"';
                $query->whereRaw($sql);
            }

            if ($this->request()->has('filter_query') && request('filter_query')) {
                $keywordToLowerCase = '%' . Str::lower(trim(request('filter_query'))) . '%';
                $sql = sprintf('(LOWER(name) LIKE "%s")', $keywordToLowerCase);
                $query->whereRaw($sql);
            }

            if ($this->request()->has('order_search') && request('order_search')) {
                if (request('order_search') == 'mostview'){
                    $query->orderBy('view', 'desc');
                }
                elseif (request('order_search') == 'mostdownload'){
                    $query->orderBy('download', 'desc');
                }
            }
            $query->orderBy('created_at', 'desc');
        });

        return $dataTable
            ->editColumn('view_count', function ($temp){
                $view = Activity::select(DB::raw("COUNT(1) as count"))
                    ->where('log_name', 'web')
                    ->where('description', 'view')
                    ->where('subject_id', $temp->id)->first();
                return $view->count;
            })
            ->editColumn('download_count', function ($temp){
                $download = Activity::select(DB::raw("COUNT(1) as count"))
                    ->join('attachments', 'activity_log.subject_id', '=', 'attachments.id')
                    ->where('log_name', 'web')
                    ->where('description', 'download')
                    ->where('attachments.document_id', $temp->id)->first();
                return $download->count;
            })
            ->addIndexColumn()
            ->addColumn('action', 'admin.documents.datatables_actions');
//            ->rawColumns(['action', 'description']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Document $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Document $model)
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
            ->minifiedAjax('',
                'if ($("#date_range_search").val().trim()){data["daterange"] = {"start": date_range_search_startDate, "end": date_range_search_endDate}}
            if ($("#filter_query").val()){data["filter_query"] = $("#filter_query").val();}
            if ($("#order_search").val()){data["order_search"] = $("#order_search").val();}')
            ->addAction(['width' => '120px', 'printable' => false, 'title' => 'Thao tác', 'className' => 'text-center'])
            ->parameters([
                'responsive'=> true,
                'dom' => '<"row"<"col-xs-12">><"row"<"col-xs-8 p-t-5"l><"col-xs-4 text-right hidden-print"B>>" +
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
            ['data' => 'name', 'name' => 'name', 'title' => 'Tên'],
            ['data' => 'privacy_label', 'name' => 'privacy_label', 'title' => 'Cấp độ bảo mật', 'className' => 'text-center','searchable' => false],
            ['data' => 'status_label', 'name' => 'status_label', 'title' => 'Trạng thái', 'className' => 'text-center','searchable' => false],
            ['data' => 'view_count', 'name' => 'view_count', 'title' => 'Lượt xem', 'className' => 'text-center','searchable' => false],
            ['data' => 'download_count', 'name' => 'download_count', 'title' => 'Lượt tải', 'className' => 'text-center','searchable' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'admin_datatable_' . time();
    }
}
