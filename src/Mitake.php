<?php

namespace Huangdijia\Mitake;

use Exception;
use Illuminate\Support\Facades\Http;

class Mitake
{
    private $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;

        // fix
        if (empty($this->config['api'])) {
            $this->config['api'] = 'http://smexpress.mitake.com.tw:9600/SmSendGet.asp';
        }
    }

    public function send($mobile = '', $message = '')
    {
        throw_if(empty($this->config['username']), new Exception("config mitake.username is undefined"));
        throw_if(empty($this->config['password']), new Exception("config mitake.password is undefined"));

        throw_if(!$this->checkMobile($mobile), new Exception("mobile error"));

        throw_if(!$this->checkMessage($message), new Exception("message error"));

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

        $response = Http::get($this->config['api'], $data)
            ->throw();

        $result = $this->parseReponse($response->body());

        throw_if(empty($result['statuscode']) || $result['statuscode'] != '1', new Exception($result['Error'], 1));

        return true;
    }

    /**
     * Parse respose
     * @param string $content
     * @return (int|string)[]|array
     */
    private function parseReponse($content = '')
    {
        $default = [
            'statuscode' => 0,
            'Error'      => 'Parse response failed',
        ];

        if (empty($content)) {
            return $default;
        }

        preg_match_all('/(\w+)=([^\r\n]+)/i', $content, $matches);
        if (empty($matches)) {
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

    /**
     * Check mobile
     * @param string $mobile
     * @return bool
     */
    private function checkMobile($mobile = '')
    {
        return preg_match('/^09\d{8}$/', $mobile) ? true : false;
    }

    /**
     * Check message
     * @param string $message
     * @return bool
     */
    private function checkMessage($message = '')
    {
        return !empty($message) ? true : false;
    }
}
