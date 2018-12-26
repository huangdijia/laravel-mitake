<?php

namespace Huangdijia\Mitake\Console;

use Illuminate\Console\Command;

class SendCommand extends Command
{
    protected $signature   = 'mitake:send {mobile : Mobile Number} {message : Message Content}';
    protected $description = 'Send a message by mitake';

    public function handle()
    {
        $mobile  = $this->argument('mobile');
        $message = $this->argument('message');
        $mitake  = app('sms.mitake');

        if (!$mitake->send($mobile, $message)) {
            $this->error($mitake->getError(), 1);
            return;
        }

        $this->info('send success!', 0);
    }
}
