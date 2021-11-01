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
    private $decrypt = [];

    // 密钥
    private $secret;

    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    public function setUid($uid)
    {
        $this->options['uid'] = $uid;
        return $this;
    }

    public function setUsername($name)
    {
        $this->options['username'] = $name;
        return $this;
    }

    public function setDomain($domain)
    {
        $this->options['domain'] = $domain;
        return $this;
    }

    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }

    public function getUid()
    {
        return $this->options['uid'];
    }

    public function getUserName()
    {
        return $this->options['username'];
    }

    public function getDomain()
    {
        return $this->options['domain'];
    }

    private function genOptions()
    {
        return implode(',', $this->options);
    }

    private function session($session)
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

    public function makeSession()
    {
        return $this->session(md5(time() . uniqid()));
    }

    public function genSessionId($session)
    {
        return $session . $this->genOptions();
    }

    private function getOptions($userinfo)
    {
        list($this->decrypt['uid'], $this->decrypt['username'], $this->decrypt['domain']) = explode(',', $userinfo);
        return $this->decrypt;
    }

    public function encrypt()
    {
        $session = str_replace('-', '', $this->makeSession());
        $sessionId = $this->genSessionId($session);
        return Aes::encrypt($sessionId, $this->secret);
    }

    public function decrypt($session)
    {
        $session = str_replace(' ', '+', rawurldecode($session));
        $str = Aes::decrypt($session, $this->secret);
        if (!$str) {
            return false;
        }
        $userinfo = substr($str, 32);
        return $this->getOptions($userinfo);
    }
}
