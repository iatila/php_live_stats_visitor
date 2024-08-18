function RealTimePages() {
    $.getJSON('index.php?page=ajax', {action:'pages'}, function(data) {
        $('div#listSites').html("");
        $.each(data.pages, function(index, e) {
            $('div#listSites').append('<div class="my-2">\n' +
                '    <p class="d-flex mb-2 text-white font-weight-bold">\n' +
                '        '+e.url+'\n' +
                '        <span class="ms-auto font-weight-bold">'+e.total+'&nbsp;</span>\n' +
                '    </p>\n' +
                '    <div class="progress progress-xs">\n' +
                '        <div class="progress-bar" role="progressbar" style="background-color:#030615;width:'+e.percent+'%" aria-valuenow="'+e.percent+'" aria-valuemin="0" aria-valuemax="100"></div>\n' +
                '    </div>\n' +
                '</div>');
        });
        $('div#pages').html("");

        var total = data.websites && data.websites.total !== undefined ? data.websites.total : 0;

        $.each(data.websites, function(index, e) {

            if(index != 'total'){
                $('div#pages').append('<div class="my-2">\n' +
                    '    <p class="d-flex mb-2 font-weight-bold">\n' +
                    '        '+e.url+'\n' +
                    '        <span class="ms-auto font-weight-bold">'+e.total+'&nbsp;</span>\n' +
                    '    </p>\n' +
                    '    <div class="progress progress-xs">\n' +
                    '        <div class="progress-bar" role="progressbar" style="background-color:#990099;width:'+e.percent+'%" aria-valuenow="'+e.percent+'" aria-valuemin="0" aria-valuemax="100"></div>\n' +
                    '    </div>\n' +
                    '</div>');
            }
        });
        $('span#pages-count').text(total);
        updateTable(data.tables);
    });
}

function updateTable(records) {
    var $tableBody = $('#listUrls tbody');
    $tableBody.empty(); // Tabloyu temizle

    $.each(records, function (index, row) {
        var $row = $('<tr></tr>');

        $row.append('<td>' + row[1] + '</td>'); // Tarih
        $row.append('<td>' + row[2] + '</td>'); // Sayfa URL
        $row.append('<td>' + row[3] + '</td>'); // IP Detay
        $row.append('<td>' + row[4] + '</td>'); // Platform
        $row.append('<td>' + row[5] + '</td>'); // Tarayıcı
        $row.append('<td>' + row[6] + '</td>'); // Ülke Bayrağı

        $tableBody.append($row);
    });
}

var choptions = {
    legend: {
        position: 'left',
        alignment: 'start',
        textStyle: {
            fontSize: 16
        }
    },
    chartArea: {right: 30, left: 5, top: 5, bottom: 5},
    width: '100%', // Grafik genişliği
    height: '100%', // Grafik yüksekliği
    pieSliceText: 'label', // Dilim üzerinde etiketleri gösterir
    pieSliceTextStyle: {
        color: 'white', // Metin rengini ayarlayın
        fontSize: 14 // Metin boyutunu ayarlayın
    },
    sliceVisibilityThreshold: 0, // Tüm dilimleri göster
    tooltip: { trigger: 'selection' },
    is3D: false, // 3D efekti açık
    backgroundColor: { fill: 'transparent' }
};

function ttp(chart) {
    google.visualization.events.addListener(chart, 'onmouseover', function(entry) {
        chart.setSelection([{ row: entry.row }]);
    });

    google.visualization.events.addListener(chart, 'onmouseout', function() {
        chart.setSelection([]);
    });
}

function RealTimeCountry() {
    $.getJSON('index.php?page=ajax', {action:'country'}, function(data) {
        // Chart için veri hazırlama
        var chartData = [['Ülkeler', 'Ziyaretçiler']];
        data.dt.forEach(function(row) {
            chartData.push([row.name + ' ... ' + row.total, row.total]);
        });

        // Chart'ı yeniden çizme
        drawChart(chartData);

        // Toplam online kullanıcı sayısını güncelleme
        $('#lives-count').text(data.online).fadeIn();
    });
}


function drawChart(chartData) {
    var data = google.visualization.arrayToDataTable(chartData);
    var chart = new google.visualization.PieChart(document.getElementById('country'));
    chart.draw(data, choptions);
    ttp(chart);
}

// Google Charts'ı başlatma
google.charts.load('current', {
    'packages': ['corechart'],
    'callback': RealTimeCountry // Sayfa yüklendiğinde grafiği çiz
});

