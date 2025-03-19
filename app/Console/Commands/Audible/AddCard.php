<?php

namespace App\Console\Commands\Audible;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AddCard extends Command
{
    protected $signature = 'command:audibleorder-card {full_name} {card_number} {month} {year}';
    protected $description = '';
    // php artisan command:amazonorder-card "Devid Villa" "4512238772601562" "3" "2028"

    public function handle()
    {
        $scriptPath = storage_path('app\public\script\audioble-order\add-card.js');

        $full_name = $this->argument('full_name');
        $card_number = $this->argument('card_number');
        $month = $this->argument('month');
        $year = $this->argument('year');

        $nodePath = 'E:\Applications\Node\node.exe';
        $process = new Process([
            $nodePath,
            $scriptPath,

            $full_name,
            $card_number,
            $month,
            $year,
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
