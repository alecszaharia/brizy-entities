<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Utils;

class Random
{
    /**
     * Generates an unique access token.
     *
     * Implementing classes may want to override this function to implement
     * other access token generation schemes.
     *
     * @return string an unique access token
     */
    public static function generateAccessToken()
    {
        if (@file_exists('/dev/urandom')) { // Get 100 bytes of random data
            $randomData = file_get_contents('/dev/urandom', false, null, 0, 100);
        } elseif (function_exists('openssl_random_pseudo_bytes')) { // Get 100 bytes of pseudo-random data
            $bytes = openssl_random_pseudo_bytes(100, $strong);
            if (true === $strong && false !== $bytes) {
                $randomData = $bytes;
            }
        }
        // Last resort: mt_rand
        if (empty($randomData)) { // Get 108 bytes of (pseudo-random, insecure) data
            $randomData = random_bytes(108);
        }

        return rtrim(strtr(base64_encode(hash('sha256', $randomData)), '+/', '-_'), '=');
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public static function generateToken($length = 32)
    {
        $bytes = random_bytes($length);

        return base_convert(bin2hex($bytes), 16, 36);
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public static function generateOauthClientIdentifier()
    {
        return substr(self::generateToken(), 0, 32);
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public static function generateOauthClientSecret()
    {
        return substr(self::generateToken(128), 0, 32);
    }
}
