<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
@if (isset($init_id))
    @foreach($init_id as $el_id)
        <script>
            {{ 'var ' . $el_id . "_startDate;"}}
            {{ 'var ' . $el_id . "_endDate;"}}
            var that = $('#{{$el_id}}');
            var autoUpdateInput = false;
            {{--if("{{ session('filter_daterange_start') }}") {--}}
            {{--    var startDate = new Date("{{ session('filter_daterange_start') }}");--}}
            {{--    autoUpdateInput = true;--}}
            {{--}--}}
            {{--else {--}}
            {{--    var startDate = new Date();--}}
            {{--    startDate.setDate(startDate.getDate() - 3);--}}
            {{--}--}}
            {{--if("{{ session('filter_daterange_end') }}") {--}}
            {{--    var endDate = new Date("{{ session('filter_daterange_end') }}");--}}
            {{--    autoUpdateInput = true;--}}
            {{--}--}}
            {{--else {--}}
            {{--    var endDate = new Date();--}}
            {{--}--}}
            $(that).daterangepicker({
                    "autoUpdateInput": autoUpdateInput,
                    // "startDate": startDate,
                    // "endDate": endDate,
                    "locale": {
                        "format": "DD/MM/YYYY",
                        "separator": " - ",
                        "applyLabel": "Chọn",
                        "cancelLabel": "Hủy",
                        "fromLabel": "Từ",
                        "toLabel": "Đến",
                        "customRangeLabel": "Tùy chọn",
                        "daysOfWeek": [
                            "CN",
                            "T2",
                            "T3",
                            "T4",
                            "T5",
                            "T6",
                            "T7"
                        ],
                        "monthNames": [
                            "Tháng 1",
                            "Tháng 2",
                            "Tháng 3",
                            "Tháng 4",
                            "Tháng 5",
                            "Tháng 6",
                            "Tháng 7",
                            "Tháng 8",
                            "Tháng 9",
                            "Tháng 10",
                            "Tháng 11",
                            "Tháng 12",
                        ],
                        "firstDay": 1
                    }
                }, (from_date, to_date) => {
                        {{$el_id . "_startDate"}} = from_date.format('YYYY-MM-DD');
                        {{$el_id . "_endDate"}} = to_date.format('YYYY-MM-DD');
                    $(that).val(from_date.format('DD/MM/YYYY') + ' - ' + to_date.format('DD/MM/YYYY'));
                    if (window.LaravelDataTables && window.LaravelDataTables['dataTableBuilder']) {
                        window.LaravelDataTables['dataTableBuilder'].draw();
                    }
                }
            );
        </script>
    @endforeach
@endif
