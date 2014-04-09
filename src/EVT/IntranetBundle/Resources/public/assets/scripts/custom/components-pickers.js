var ComponentsPickers = function () {

    var handleDateRangePickers = function () {

        if (!jQuery().daterangepicker) {
            return;
        }

        var startDateEvent = ($('#reportrange_start').val() != '' ? moment($('#reportrange_start').val()) : moment().subtract('days', 29));
        var endDateEvent = ($('#reportrange_end').val() != '' ? moment($('#reportrange_end').val()) : moment());

        $('#reportrange').daterangepicker({
                opens: (App.isRTL() ? 'left' : 'right'),
                startDate: startDateEvent,
                endDate: endDateEvent,
                minDate: '01/01/2014',
                maxDate: '31/12/2020',
                dateLimit: {
                    days: 366
                },
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract('days', 29), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                },
                buttonClasses: ['btn'],
                applyClass: 'green',
                cancelClass: 'default',
                format: 'DD/MM/YYYY',
                separator: ' to ',
                locale: {
                    applyLabel: 'Apply',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            },
            function (start, end) {
                console.log("Callback has been called!");
                $('#reportrange span').html(start.format(genericDateFormat) + ' - ' + end.format(genericDateFormat));
            }
        );
        //Set the initial state of the picker label
        $('#reportrange span').html( startDateEvent.format(genericDateFormat)+ ' - ' + endDateEvent.format(genericDateFormat));

        $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
            $('#reportrange_start').val(picker.startDate.format('YYYY-MM-DD'));
            $('#reportrange_end').val(picker.endDate.format('YYYY-MM-DD'));
        });

        var startDateCreate = ($('#reportrange_create_start').val() != '' ? moment($('#reportrange_create_start').val()) : moment().subtract('days', 29));
        var endDateCreate = ($('#reportrange_create_end').val() != '' ? moment($('#reportrange_create_end').val()) : moment());

        $('#reportrange_create').daterangepicker({
                opens: (App.isRTL() ? 'left' : 'right'),
                startDate: startDateCreate,
                endDate: endDateCreate,
                minDate: '01/01/2014',
                maxDate: '31/12/2020',
                dateLimit: {
                    days: 366
                },
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract('days', 29), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                },
                buttonClasses: ['btn'],
                applyClass: 'green',
                cancelClass: 'default',
                format: 'DD/MM/YYYY',
                separator: ' to ',
                locale: {
                    applyLabel: 'Apply',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            },
            function (start, end) {
                console.log("Callback has been called!");
                $('#reportrange_create span').html(start.format(genericDateFormat) + ' - ' + end.format(genericDateFormat));
            }
        );
        //Set the initial state of the picker label
        $('#reportrange_create span').html(startDateCreate.format(genericDateFormat) + ' - ' + endDateCreate.format(genericDateFormat));

        $('#reportrange_create').on('apply.daterangepicker', function(ev, picker) {
            $('#reportrange_create_start').val(picker.startDate.format('YYYY-MM-DD'));
            $('#reportrange_create_end').val(picker.endDate.format('YYYY-MM-DD'));
        });
    }

  return {
        //main function to initiate the module
        init: function () {
            handleDateRangePickers();
        }
    };

}();