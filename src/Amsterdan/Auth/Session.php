<?php
namespace Amsterdan\Auth;

class Session
{
    // 要设置的信息
    private $options = [
        'uid' => 0,
        'username' => '',
        'domain' => '',
    ];

    // 解密后的内容
    private $options_data = [];

    // 密钥
    private $secret;

    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    /**
     * 加密方法
     */
    public function encrypt()
    {
        $session = str_replace('-', '', $this->makeSession());
        $sessionId = $session . $this->formatOptions();
        return Aes::encrypt($sessionId, $this->secret);
    }

    /**
     * 解密方法
     *
     * @param string $str 密文
     */
    public function decrypt(string $str)
    {
        if (empty($str)) {
            return false;
        }

        $session = str_replace(' ', '+', rawurldecode($str));
        $str = Aes::decrypt($session, $this->secret);
        if (!$str) {
            return false;
        }

        $options = substr($str, 32);
        return $this->getOptions($options);
    }

    /**
     * 设置用户id
     */
    public function setUid($uid)
    {
        $this->options['uid'] = $uid;
        return $this;
    }

    /**
     * 设置用户名
     */
    public function setUsername($name)
    {
        $this->options['username'] = $name;
        return $this;
    }

    /**
     * 设置域名
     */
    public function setDomain($domain)
    {
        $this->options['domain'] = $domain;
        return $this;
    }

    /**
     * 获取用户id
     */
    public function getUid()
    {
        return $this->options['uid'];
    }

    /**
     * 获取用户名
     */
    public function getUserName()
    {
        return $this->options['username'];
    }

    /**
     * 获取域名
     */
    public function getDomain()
    {
        return $this->options['domain'];
    }

    /**
     * 返回解密后的内容
     */
    private function formatOptions()
    {
        return implode(',', $this->options);
    }

    /**
     * 格式化 session
     */
    private function formatSession($session)
    {
        $parts = [];
        $pattern = [
            0 => 8,
            8 => 4,
            12 => 4,
            16 => 4,
            20 => 12,
        ];

        foreach ($pattern as $start => $len) {
            $parts[] = substr($session, $start, $len);
        }

        return implode('-', $parts);
    }

    /**
     * 生成 session
     *
     * @param bool $format  格式化
     */
    public function makeSession(bool $format = false)
    {
        $session = md5(time() . uniqid());
        return $format ? $this->formatSession($session) : $session;
    }

    /**
     * 获取设置的内容
     */
    private function getOptions($options)
    {
        if (!empty($this->options_data)) {
            return $this->options_data;
        }

        list($this->options_data['uid'], $this->options_data['username'], $this->options_data['domain']) = explode(',', $options);
        return $this->options_data;
    }
}
