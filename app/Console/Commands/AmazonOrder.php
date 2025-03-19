<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AmazonOrder extends Command
{
    protected $signature = 'command:amazon-order {email} {password} {country} {full_name} {number} {address} {city} {state} {zip_code} {card_number} {month} {year} {add_to_cart1} {add_to_cart2} {free_book}';
    protected $description = 'Pass individual card details to Node.js script';
    // php artisan command:amazon-order "david83245064@outlook.com" "Dav782" "US" "Devid Villa" "(541) 343-3477" "305 W 7th Ave" "Eugene" "OR" "97401" "4512238772601562" "3" "2028" "https://www.amazon.com/dp/B09RNDQCV3" "https://www.amazon.com/dp/B07VHKV152" "https://www.amazon.com/dp/B0CW1J837H"

    public function handle()
    {
        $scriptPath = storage_path('app/public/script/amazon-order.js');

        $email = $this->argument('email');
        $password = $this->argument('password');
        $country = $this->argument('country');
        $full_name = $this->argument('full_name');
        $number = $this->argument('number');
        $address = $this->argument('address');
        $city = $this->argument('city');
        $state = $this->argument('state');
        $zip_code = $this->argument('zip_code');
        $card_number = $this->argument('card_number');
        $month = $this->argument('month');
        $year = $this->argument('year');
        $add_to_cart1 = $this->argument('add_to_cart1');
        $add_to_cart2 = $this->argument('add_to_cart2');
        $free_book = $this->argument('free_book');

        $nodePath = 'E:\Applications\Node\node.exe';
        $process = new Process([
            $nodePath,
            $scriptPath,

            $email,
            $password,
            $country,
            $full_name,
            $number,
            $address,
            $city,
            $state,
            $zip_code,
            $card_number,
            $month,
            $year,
            $add_to_cart1,
            $add_to_cart2,
            $free_book,
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
