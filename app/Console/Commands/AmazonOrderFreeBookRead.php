<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AmazonOrderFreeBookRead extends Command
{
    protected $signature = 'command:amazonorder-read {free_book}';
    protected $description = '';
    // php artisan command:amazonorder-read "B07CSVHKRM"

    public function handle()
    {
        $scriptPath = storage_path('app\public\script\amazon-order\freebook-read.js');

        $free_book = $this->argument('free_book');

        $nodePath = 'E:\Applications\Node\node.exe';
        $process = new Process([
            $nodePath,
            $scriptPath,

            $free_book
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
