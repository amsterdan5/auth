<?php
namespace Amsterdan\Auth;

use phpseclib3\Crypt\AES as LibAes;

class Aes
{
    const CIPHER_IV = 'jagdjagfjkgjkask';

    /**
     * AES instance
     *
     * @param  string     $secret
     * @return LibAes
     */
    public static function instance($secret)
    {
        $cipher = new LibAes('cbc');
        $cipher->setKey(hash('sha256', $secret, true));
        $cipher->setIv(self::CIPHER_IV);
        return $cipher;
    }

    /**
     * Encrypts a plain text
     *
     * @param  string   $text
     * @param  string   $secret
     * @return string
     */
    public static function encrypt($text, $secret)
    {
        return base64_encode(self::instance($secret)->encrypt($text));
    }

    /**
     * Decrypts a cipher text
     *
     * @param  string   $text
     * @param  string   $secret
     * @return string
     */
    public static function decrypt($text, $secret)
    {
        return self::instance($secret)->decrypt(base64_decode($text));
    }

}
