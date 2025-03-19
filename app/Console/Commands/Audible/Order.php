<?php

namespace App\Console\Commands\Audible;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Order extends Command
{
    protected $signature = 'command:audible-order {audible_book} {card_number}';
    protected $description = '';
    // php artisan command:amazonorder-cart "B09RNDQCV3" "B098T78VGZ"

    public function handle()
    {
        $scriptPath = storage_path('app\public\script\audioble-order\audioble.js');

        $audible_book = $this->argument('audible_book');
        $card_number = $this->argument('card_number');
        $last_4_digits = substr($card_number, -4);

        $nodePath = 'E:\Applications\Node\node.exe';
        $process = new Process([
            $nodePath,
            $scriptPath,

            $audible_book,
            $last_4_digits,
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
