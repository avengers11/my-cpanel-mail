<?php

namespace App\Console\Commands\Audible;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Login extends Command
{
    protected $signature = 'command:audibleorder-login {email} {password}';
    protected $description = '';
    // php artisan command:amazonorder-login "david83245064@outlook.com" "Dav782"

    public function handle()
    {
        $scriptPath = storage_path('app\public\script\audioble-order\login.js');

        $email = $this->argument('email');
        $password = $this->argument('password');

        $nodePath = 'E:\Applications\Node\node.exe';
        $process = new Process([
            $nodePath,
            $scriptPath,

            $email,
            $password
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
