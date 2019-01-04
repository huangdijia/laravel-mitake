<?php

namespace Huangdijia\Mitake;

use Huangdijia\Curl\Facades\Curl;

class Mitake
{
    private $config = [];
    private $apis   = [
        'send_sms'      => 'http://smexpress.mitake.com.tw:9600/SmSendGet.asp',
        'check_accinfo' => '',
    ];
    private $init = true;
    private $errno;
    private $error;

    public function __construct($config)
    {
        if (empty($config['username'])) {
            $this->error = "config mitake.username is undefined";
            $this->errno = 101;
            $this->init  = false;
            return;
        }
        if (empty($config['password'])) {
            $this->error = "config mitake.password is undefined";
            $this->errno = 102;
            $this->init  = false;
            return;
        }

        $this->config = $config;
    }

    public function send($mobile = '', $message = '')
    {
        if (!$this->init) {
            return false;
        }

        // 默认错误信息
        $this->error = null;
        $this->errno = null;

        // 检测数据
        if (!$this->checkMobile($mobile)) {
            return false;
        }

        if (!$this->checkMessage($message)) {
            return false;
        }

        $data = [
            'username' => $this->config['username'],
            'password' => $this->config['password'],
            'type'     => $this->config['type'] ?? 'now',
            'encoding' => $this->config['encoding'] ?? 'big5',
            'dstaddr'  => $mobile,
            'smbody'   => iconv('utf-8', $this->config['encoding'] ?? 'big5', $message),
            // 'vldtime'    => $this->config['vldtime'],
            // 'dlvtime'    => $this->config['dlvtime'],
        ];

        $url      = $this->apis['send_sms'] ?? '';
        $response = Curl::get($url, $data, true);
        $result   = $this->parseReponse($response);

        if (empty($result['statuscode']) || $result['statuscode'] != '1') {
            $this->error = $result['Error'];
            return false;
        }

        return true;
    }

    private function parseReponse($content = '')
    {
        $default = [
            'statuscode' => 0,
            'Error'      => '解析返回结果错误',
        ];

        if (empty($content)) {
            $this->errno = 401;
            return $default;
        }

        preg_match_all('/(\w+)=([^\r\n]+)/i', $content, $matches);
        if (empty($matches)) {
            $this->errno = 402;
            return $default;
        }

        $result = [];
        foreach ($matches[1] as $i => $key) {
            $result[$key] = isset($matches[2][$i]) ? $matches[2][$i] : '';
            if ($key == 'Error') {
                $result[$key] = iconv('big5', 'utf-8', $result[$key]);
            }
        }

        $result = array_merge($default, $result);

        return $result;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getErrno()
    {
        return $this->errno;
    }

    private function checkMobile($mobile = '')
    {
        return preg_match('/^09\d{8}$/', $mobile);
    }

    private function checkMessage($message = '')
    {
        return !empty($message) ? true : false;
    }
}
