@section('css')
    @include('layouts.datatables_css')
    @include('admin.layouts.daterange_picker_css')
@endsection

{!! $dataTable->table(['width' => '100%', 'class' => 'table table-striped table-bordered']) !!}

@push('scripts')
    @include('admin.layouts.daterange_picker_js', ['init_id' => ['date_range_search']])
    @include('layouts.datatables_js')
    {!! $dataTable->scripts() !!}
    <script>
        var queryTypingTimer;
        var doneTypingInterval = 500;
        var $input = $('#filter_query');
        $input.on('keyup', function () {
            clearTimeout(queryTypingTimer);
            queryTypingTimer = setTimeout(doneFilterTyping, doneTypingInterval);
        });
        $input.on('keydown', function () {
            clearTimeout(queryTypingTimer);
        });
        function doneFilterTyping () {
            if (window.LaravelDataTables && window.LaravelDataTables['dataTableBuilder']){
                window.LaravelDataTables['dataTableBuilder'].search($input.val()).draw();
            }
        }
        $('#order_search').on('change', function () {
            if (window.LaravelDataTables && window.LaravelDataTables['dataTableBuilder']){
                window.LaravelDataTables['dataTableBuilder'].draw();
            }
        });
    </script>
@endpush
