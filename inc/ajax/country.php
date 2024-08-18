<?php if (!defined('X')) die('Deny Access');
header('Content-type: application/json; charset=utf-8');

$lives = $db->fetchStats(
    'live_stats',
    ['lv_country', 'COUNT(lv_id) AS total'],
    'lv_date >= NOW() - INTERVAL :interval MINUTE GROUP BY lv_country ORDER BY COUNT(lv_id) DESC',
    ['interval' => $cnf['time_db']]
);

$arr = [
    'online' => 0,
    'dt' => []
];

if ($lives) {
    foreach ($lives as $li) {
        $arr['online'] += (int) $li['total'];
        $upper = strtoupper($li['lv_country']);
        $countryName = $countlist[$upper] ?? $upper;
        $arr['dt'][] = [
            'name' => htmlspecialchars($countryName, ENT_QUOTES, 'UTF-8'),
            'total' => (int) $li['total']
        ];
    }
}

echo json_encode($arr);