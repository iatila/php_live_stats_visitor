<?php if (!defined('X')) die('Deny Access');
header('Content-type: application/json; charset=utf-8');

$pages = $db->fetchStats(
    'live_stats',
    ['lv_page', 'COUNT(lv_id) AS total'],
    'lv_date >= NOW() - INTERVAL :interval MINUTE GROUP BY lv_page ORDER BY COUNT(lv_id) DESC',
    ['interval' => $cnf['time_db']]
);

function maskStringWithEnds($input) {
    // Dizgenin uzunluğunu al
    $length = strlen($input);

    // Eğer dizgenin uzunluğu 6'dan küçükse, orijinal dizgeyi döndür
    if ($length <= 6) {
        return $input;
    }

    // İlk 3 karakteri al
    $firstThree = substr($input, 0, 3);

    // Son 3 karakteri al
    $lastThree = substr($input, -3);

    // Kalan karakterlerin sayısını hesapla ve onları yıldızlarla gizle
    $maskedPart = str_repeat('*', $length - 6);

    // İlk 3 karakteri, gizlenmiş kısmı ve son 3 karakteri birleştir
    return $firstThree . $maskedPart . $lastThree;
}
$json = [];
if ($pages) {
    $sum = array_sum(array_column($pages, 'total'));
    foreach ($pages as $p) {
        $parse = parse_url($p['lv_page']);
        $total = (int)$p['total'];
        $host = $parse['host'] ?? '';
        $path = $parse['path'] ?? '';
        $query = $parse['query'] ?? '';
        $percent = ($sum > 0) ? ($total / $sum * 100) : 0;
        $json['pages'][] = [
            'url' => Utils::Shorter(
                sprintf('%s%s%s', $host, $path, $query ? '?'.$query : ''),
                40
            ),
            'total' => $total,
            'percent' => $percent
        ];
    }
}

$website = $db->fetchStats(
    'live_stats_website',
    ['web_url', 'web_hit'],
    'DATE(web_date) = CURDATE() GROUP BY web_url ORDER BY web_hit DESC'
);


$total = 0;
if ($website) {
    foreach ($website as $wb) {
        $json['websites'][] = [
            'url' => $wb['web_url'],
            'total' => $wb['web_hit']
        ];
        $total += $wb['web_hit'];
    }
    $json['websites']['total'] = $total;
}

$tables = $db->fetchStats(
    'live_stats',
    ['lv_id','lv_date','lv_page','lv_ip','lv_platform','lv_browser','lv_country'],
    'lv_date >= NOW() - INTERVAL :interval MINUTE GROUP BY lv_ip ORDER BY lv_id DESC',
    ['interval' => $cnf['time_db']]
);
if ($tables){
    foreach ($tables as $row) {
        $plIcon = str_replace('.', '', $row['lv_platform']);
        $json['tables'][] = [
                1 => '<i class="fa-solid fa-clock"></i> ' . date('H:i:s', strtotime('-3 minutes', strtotime($row['lv_date']))),
                2 => '<i class="text-info fa-solid fa-globe"></i> ' . str_replace('http://', '', $row['lv_page']),
                3 => '<i class="fa-solid fa-map-marker"></i> ' . ipDetail(maskStringWithEnds($row['lv_ip'])),
                4 => '<span  title="' . ($Osplatforms[$row['lv_platform']]['name'] ?? 'Unknown') . '" class="bw-icon os-icon-' . $plIcon . '"></span>',
                5 => '<span  title="' . ($devices[$row['lv_browser']]['name'] ?? 'Unknown') . '" class="bw-icon bw-icon-' . $row['lv_browser'] . '"></span>',
                6 => CountryFlag($row['lv_country'],$countlist)
        ];
    }
}


echo json_encode($json);