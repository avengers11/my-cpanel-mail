<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AmazonAudiobleOrder extends Command
{
    protected $signature = 'command:amazon-audioble-order {email} {password} {cart_item} {audioble}';
    //php artisan command:amazon-audioble-order "laura25375172@outlook.com" "Lau912" "https://www.audible.com/search?keywords=The+rocket+and+the&k=The+rocket+and+the" "https://www.audible.com/pd/The-Rocket-and-the-Reformer-Audiobook/B0DWQ9LBFG?eac_link=jccokvhORFag&ref=web_search_eac_asin_1&eac_selected_type=asin&eac_selected=B0DWQ9LBFG&qid=7W13XfAJTz&eac_id=130-6922102-5167403_7W13XfAJTz&sr=1-2"
    protected $description = 'Pass individual card details to Node.js script';

    public function handle()
    {
        $scriptPath = storage_path('app/public/script/audioble-order.js');

        $email = $this->argument('email');
        $password = $this->argument('password');
        $cart_item = $this->argument('cart_item');
        $audioble = $this->argument('audioble');

        $nodePath = 'E:\Applications\Node\node.exe';
        $process = new Process([
            $nodePath,
            $scriptPath,

            $email,
            $password,
            $cart_item,
            $audioble
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
