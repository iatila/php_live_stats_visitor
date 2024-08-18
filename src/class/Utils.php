<?php
class Utils {
    /**
     * Verilen bir dizgeden boşlukları kaldırır.
     *
     * @param string $string Dizge
     * @return string Boşlukları kaldırılmış dizge
     */
    public static function Rspace(string $string): string {
        return preg_replace('/\s+/', '', $string);
    }

    /**
     * Verilen bir URL'den domain adını alır.
     *
     * @param string $url URL
     * @return string|false Domain adı veya false
     */
    public static function GetDomain(string $url) {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return self::Rspace($regs['domain']);
        }
        return false;
    }

    /**
     * Kullanıcının IP adresini alır.
     *
     * @return string IP adresi
     */
    public static function GetIp(): string {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR', 'HTTP_CF_CONNECTING_IP') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (array_map('trim', explode(',', $_SERVER[$key])) as $rw) {
                    if (filter_var($rw, FILTER_VALIDATE_IP) !== false) {
                        return $rw;
                    }
                }
            }
        }
        return 'unknown';
    }

    /**
     * Verilen bir dizgeyi kısaltır ve nokta ekler.
     *
     * @param string $words Kısaltılacak dizge
     * @param int $str Kısaltma uzunluğu
     * @return string Kısaltılmış dizge
     */
    public static function Shorter(string $words, int $str = 10): string {
        if (strlen($words) > $str) {
            $words = mb_substr($words, 0, $str, "UTF-8") . '.';
        }
        return $words;
    }
}
