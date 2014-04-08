var Charts = function () {

    return {
        //main function to initiate the module
        initCharts: function () {

            if (!jQuery.plot) {
                return;
            }

            var data = [];
            var totalPoints = 250;

            // random data generator for plot charts

            function getRandomData() {
                if (data.length > 0) data = data.slice(1);
                // do a random walk
                while (data.length < totalPoints) {
                    var prev = data.length > 0 ? data[data.length - 1] : 50;
                    var y = prev + Math.random() * 10 - 5;
                    if (y < 0) y = 0;
                    if (y > 100) y = 100;
                    data.push(y);
                }
                // zip the generated y values with the x values
                var res = [];
                for (var i = 0; i < data.length; ++i) res.push([i, data[i]])
                return res;
            }

            function chart5() {
                var leads = [];
                var leadsLabel = [];
                var leadsPorDate = [];

                for (var i = 0; i < statsLeadRawData.length; i += 1) {
                    if (leadsPorDate[statsLeadRawData[i].date] != undefined) {
                        leadsPorDate[statsLeadRawData[i].date] += statsLeadRawData[i].number;
                    } else {
                        leadsPorDate[statsLeadRawData[i].date] = statsLeadRawData[i].number;
                    }
                }

                var index = 0;
                for (key in leadsPorDate) {
                    if (leadsPorDate.hasOwnProperty(key)) {
                        leads.push([index, leadsPorDate[key]]);
                        leadsLabel.push([index, key.substring(0,10)]);
                        index++;
                    };
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