<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AmazonOrderAddAddress extends Command
{
    protected $signature = 'command:amazonorder-address {country} {full_name} {number} {address} {city} {state} {zip_code}';
    protected $description = '';
    // php artisan command:amazonorder-address "US" "Devid Villa" "(541) 343-3477" "305 W 7th Ave" "Eugene" "OR" "97401"

    public function handle()
    {
        $scriptPath = storage_path('app\public\script\amazon-order\address.js');

        $country = $this->argument('country');
        $full_name = $this->argument('full_name');
        $number = $this->argument('number');
        $address = $this->argument('address');
        $city = $this->argument('city');
        $state = $this->argument('state');
        $zip_code = $this->argument('zip_code');

        $nodePath = 'E:\Applications\Node\node.exe';
        $process = new Process([
            $nodePath,
            $scriptPath,

            $country,
            $full_name,
            $number,
            $address,
            $city,
            $state,
            $zip_code,
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
