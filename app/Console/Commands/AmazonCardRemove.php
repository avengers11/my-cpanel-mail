<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AmazonCardRemove extends Command
{
    protected $signature = 'app:amazon-card-remove {profile_id}';
    protected $description = 'Pass individual card details to Node.js script';

    public function handle()
    {
        $scriptPath = storage_path('app/public/script/amazon_card_remove.js');
        $profile_id = $this->argument('profile_id');

        $nodePath = 'E:\Applications\Node\node.exe';
        $process = new Process([
            $nodePath,
            $scriptPath,
            $profile_id,
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
