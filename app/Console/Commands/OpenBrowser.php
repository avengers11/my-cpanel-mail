<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class OpenBrowser extends Command
{
    protected $signature = 'app:open-browser'; // php artisan app:open-browser
    protected $description = 'Pass individual card details to Node.js script';

    public function handle()
    {
        $scriptPath = storage_path('app/public/script/open_browser.js');

        $nodePath = 'E:\Applications\Node\node.exe';
        $process = new Process([
            $nodePath,
            $scriptPath,
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
