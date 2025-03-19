<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AmazonOrderAddCart extends Command
{
    protected $signature = 'command:amazonorder-cart {cart1} {cart2}';
    protected $description = '';
    // php artisan command:amazonorder-cart "B09RNDQCV3" "B098T78VGZ"

    public function handle()
    {
        $scriptPath = storage_path('app\public\script\amazon-order\add-cart.js');

        $cart1 = $this->argument('cart1');
        $cart2 = $this->argument('cart2');

        $nodePath = 'E:\Applications\Node\node.exe';
        $process = new Process([
            $nodePath,
            $scriptPath,

            $cart1,
            $cart2
        ]);

        $process->setTimeout(300); 
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->info('Node.js script executed successfully!');
        $this->line($process->getOutput());
    }
}
