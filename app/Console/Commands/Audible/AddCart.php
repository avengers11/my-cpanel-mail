<?php

namespace App\Console\Commands\Audible;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AddCart extends Command
{
    protected $signature = 'command:audibleorder-cart {keyword}';
    protected $description = '';
    // php artisan command:amazonorder-cart "B09RNDQCV3" "B098T78VGZ"

    public function handle()
    {
        $scriptPath = storage_path('app\public\script\audioble-order\add-cart.js');

        $keyword = $this->argument('keyword');

        $nodePath = 'E:\Applications\Node\node.exe';
        $process = new Process([
            $nodePath,
            $scriptPath,

            $keyword
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
