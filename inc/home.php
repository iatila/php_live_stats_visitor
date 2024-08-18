<?php if (!defined('X')) die('Deny Access');?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    <title>Online Visitor</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/7.2.3/css/flag-icons.min.css" rel="stylesheet">
    <link href="assets/custom.css?v=<?=time()?>" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>
<main class="app-content">
    <div id="general" class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <div class="card shadow  bg-analitik">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="card-title font-weight-bold text-white">
                                <span class="lstick lstick-main"></span>Online Ziyaretçiler
                            </h6>
                        </div>
                        <div id="lives-count" class="lives-count">0</div>
                        <hr class="my-2">
                        <div id="listSites" class="card-inline sc sc-main" style="height: 250px;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-12">
                <div class="shadow card">
                    <div class="card-body">
                        <div class="d-flex no-block bt-1">
                            <div>
                                <h6 class="card-title font-weight-bold">
                                    <span class="lstick lstick-primary"></span>Popüler Sayfalar
                                </h6>
                            </div>
                            <div class="d-none d-md-block badge-pg">
                                <span id="pages-count" class="f-16 bg-primary badge text-white">0</span>
                            </div>
                        </div>
                        <div id="pages" class="card-inline sc sc-primary">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="shadow  card">
                    <div class="card-body">
                        <div class="d-flex no-block bt-1">
                            <div>
                                <h6 class="card-title font-weight-bold">
                                    <span class="lstick lstick-main"></span>Ülkeler
                                </h6>
                            </div>
                            <div class="d-none d-md-block fullscr"><a href="javascript:;" id="fullscr" class="bg-main badge text-white"><i class="fa-solid fa-maximize"></i> </a></div>
                        </div>
                        <div class="chart" style="min-height: 440px" id="country">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div  class="tile shadow">
                    <div class="tile-body">
                        <div>
                            <table id="listUrls" class="table table-bordered table-hover mt-4"  style="width: 100%;">
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="//www.gstatic.com/charts/loader.js"></script>
<script src="assets/functions.js"></script>
<script>
    $(function() {
        RealTimePages();
        RealTimeCountry();
        setInterval(RealTimeCountry,RealTimePages, <?=$cnf['time_js']?>);

        $('#fullscr').on('click', function() {
            var $general = $('#general');
            var $icon = $(this).find('i');

            if ($general.hasClass('container')) {
                $general.removeClass('container').addClass('container-fluid');
                $icon.removeClass('fa-maximize').addClass('fa-minimize');
            } else {
                $general.removeClass('container-fluid').addClass('container');
                $icon.removeClass('fa-minimize').addClass('fa-maximize');
            }
            RealTimeCountry();
        });
    });
</script>
</body>
</html>