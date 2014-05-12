var Charts = function () {

    return {
        //main function to initiate the module
        initCharts: function () {

            if (!jQuery.plot) {
                return;
            }

            function chart5() {
                if (typeof statsLeadRawData == 'undefined') return;
                var leads = [];
                var leadsLabel = [];
                var leadsPorDate = [];

                for (var i = 0; i < statsLeadRawData.length; i += 1) {
                    if ($('#select2_sample4').val() == '' || ($('#select2_sample4').val() == statsLeadRawData[i].vertical)) {
                        if (leadsPorDate[statsLeadRawData[i].date] != undefined) {
                            leadsPorDate[statsLeadRawData[i].date] += statsLeadRawData[i].number;
                        } else {
                            leadsPorDate[statsLeadRawData[i].date] = statsLeadRawData[i].number;
                        }
                    }
                }

                var index = 0;

                var currentLastDate = '';
                var currentDate = statsLeadFromDate;

                for (key in leadsPorDate) {
                    if (leadsPorDate.hasOwnProperty(key)) {

                        if (currentLastDate == '') {
                            currentLastDate = statsLeadFromDate;
                        }

                        currentDate = key.substring(0, 10);

                        // Fill days with no datas
                        while (currentLastDate != currentDate) {
                            leads.push([index, 0]);
                            leadsLabel.push([index, currentLastDate]);
                            index++;

                            var nextDay = new Date(currentLastDate);
                            nextDay.setDate(nextDay.getDate() + 1);

                            currentLastDate = nextDay.toISOString().substring(0,10);
                        }

                        leads.push([index, leadsPorDate[key]]);
                        leadsLabel.push([index, key.substring(0,10)]);
                        index++;
                    };
                }

                // Fill days with no datas till to-date
                while (currentLastDate != statsLeadToDate) {
                    leads.push([index, 0]);
                    leadsLabel.push([index, currentLastDate]);
                    index++;

                    var nextDay = new Date(currentLastDate);
                    nextDay.setDate(nextDay.getDate() + 1);

                    currentLastDate = nextDay.toISOString().substring(0,10);
                }

                var stack = 0,
                    bars = true,
                    lines = false,
                    steps = false;

                function plotWithOptions() {
                    $.plot($("#chart_5"), 

                        [{
                            label: "leads",
                            data: leads,
                            lines: {
                                lineWidth: 1,
                            },
                            shadowSize: 0
                        }]

                        , {
                            series: {
                                stack: stack,
                                lines: {
                                    show: lines,
                                    fill: true,
                                    steps: steps,
                                    lineWidth: 0, // in pixels
                                },
                                bars: {
                                    show: bars,
                                    barWidth: 0.5,
                                    lineWidth: 0, // in pixels
                                    shadowSize: 0,
                                    align: 'center'
                                }
                            },
                            xaxis: {
                                ticks: leadsLabel
                            },
                            grid: {
                                tickColor: "#eee",
                                borderColor: "#eee",
                                borderWidth: 1
                            }
                        }                       
                    );
                }   

                plotWithOptions();
            }

            //graph
            chart5();

        }

    };

}();