var options = {
    series: [{
        name: 'Website Blog',
        type: 'column',
        data: [440, 505, 414, 671, 227, 413, 201, 352, 752, 320, 257, 160]
    }, {
        name: 'Social Media',
        type: 'line',
        data: [23, 42, 35, 27, 43, 22, 17, 31, 22, 22, 12, 16]
    }],
    chart: {
        height: 350,
        type: 'line',
    },
    stroke: {
        width: [0, 4]
    },
    title: {
        text: ''
    },
    dataLabels: {
        enabled: true,
        enabledOnSeries: [1]
    },
    labels: ['01 Jan 2021', '02 Jan 2021', '03 Jan 2021', '04 Jan 2021', '05 Jan 2021', '06 Jan 2021', '07 Jan 2021', '08 Jan 2021', '09 Jan 2021', '10 Jan 2021', '11 Jan 2021', '12 Jan 2021'],
    xaxis: {
        type: 'datetime'
    },
    yaxis: [{
        title: {
            text: 'Today Sale',
        },

    }, {
        opposite: true,
        title: {
            text: 'Product Stock'
        }
    }]
};

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();