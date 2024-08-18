<?php ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once __DIR__.'/../src/config.php';

$fullUrl = htmlspecialchars($_SERVER['HTTP_REFERER'] ?? '');

$website = Utils::GetDomain($fullUrl);

////////// Site Adresi Geçerli mi? ///////////////////
if (strlen($website) < 3) {
    die(json_encode('Error ~ 100'));
}

//////////// Local Erişim Engelle ///////////////
$local = Utils::GetDomain($_SERVER['SERVER_NAME']);
if ($local == $website) {
    die(json_encode('Error ~ 101'));
}



////////////////////////////
$ip = Utils::GetIp();

$myCountry = strtolower(Utils::Rspace($_SERVER['HTTP_CF_IPCOUNTRY'] ?? 'NoC'));

$website = strlen($website) > 1 ? $website : 'Bilinmiyor';

$browser = get_browser($_SERVER['HTTP_USER_AGENT'], true); // Tarayıcı bilgilerini al
$platform = strtolower(trim($browser['platform'] ?? 'unknown')); // Tarayıcı platformunu al
$agent = md5($platform . $ip);

$bww = explode('-', Utils::Rspace(preg_replace('/\W+/', '-', strtolower($browser['parent'] ?? 'unknown')))); // Parent browser bilgisi alındı

$brwsr = Utils::Rspace($bww[0] ?? 'unknown');

$fullUrl = preg_replace('(^https?://)', '', $fullUrl); // http veya https kaldırılır

$query = $db->getConnection()->prepare("CALL sp_liveStats(?,?,?,?,?,?,?)");
$query->execute(array(
    $website, $fullUrl, $agent, $myCountry, $ip, $platform, $brwsr
));

////////////////////////////////////
ob_end_flush();