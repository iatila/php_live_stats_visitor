<?php
function CountryFlag(string $countryCode, array $countlist): string
{
    $title = 'Bilinmiyor';
    $countryCode = strtolower($countryCode);
    if (isset($countlist[$countryCode]) && $countlist[$countryCode] !== 'Bilinmiyor') {
        $title = $countlist[$countryCode];
    }

    return '<span data-toggle="tooltip" data-placement="bottom" title="' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '" class="fi fi-' . htmlspecialchars($countryCode, ENT_QUOTES, 'UTF-8') . '"></span>';
}


function ipDetail(string $ip): string
{
    return '<a href="http://ip-api.com/#' . htmlspecialchars($ip, ENT_QUOTES, 'UTF-8') . '" target="_blank">' . htmlspecialchars($ip, ENT_QUOTES, 'UTF-8') . '</a>';
}