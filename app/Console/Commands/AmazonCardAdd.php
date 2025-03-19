<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AmazonCardAdd extends Command
{
    protected $signature = 'app:amazon-card-add {name} {card} {month} {year} {amazon_id}';
    protected $description = 'Pass individual card details to Node.js script';

    public function handle()
    {
        $scriptPath = storage_path('app/public/script/amazon_card_add.js');

        $name = $this->argument('name');
        $card = $this->argument('card');
        $month = $this->argument('month');
        $year = $this->argument('year');
        $amazon_id = $this->argument('amazon_id');

        $nodePath = 'E:\Applications\Node\node.exe';
        $process = new Process([
            $nodePath,
            $scriptPath,
            $name,
            $card,
            $month,
            $year,
            $amazon_id,
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
