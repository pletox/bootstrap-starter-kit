<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Minishlink\WebPush\VAPID;

#[Signature('app:generate-vapid-keys')]
#[Description('Generate VAPID keys for browser push notifications.')]
class GenerateVapidKeys extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $keys = VAPID::createVapidKeys();

        $this->components->info('Add these values to your environment:');
        $this->line('VAPID_PUBLIC_KEY='.$keys['publicKey']);
        $this->line('VAPID_PRIVATE_KEY='.$keys['privateKey']);

        return self::SUCCESS;
    }
}
