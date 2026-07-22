<?php

namespace ECidade\Configuracao\Api\Generator;

use JSON;

class HashGenerator
{
    const ALGORITHM = 'aes-256-cfb';
    const IV = 'ECI-CRYP-999-666';

    /**
     * @param array $data
     * @param string $secret
     * @return string
     */
    public static function encrypt(array $data, $secret)
    {
        $json = JSON::create()->stringify($data);
        return openssl_encrypt($json, self::ALGORITHM, $secret, 0, self::IV);
    }

    /**
     * @param string $hash
     * @param string $secret
     * @return boolean|array
     */
    public static function decrypt($hash, $secret)
    {
        $json = openssl_decrypt($hash, self::ALGORITHM, $secret, 0, self::IV);
        $data = JSON::create()->parse($json);

        if (is_null($data)) {
            return false;
        }

        return (array) $data;
    }
}
