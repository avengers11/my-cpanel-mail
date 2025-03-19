<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class FBPostToGroup extends Command
{
    protected $signature = 'facebook:post {group_id} {message} {image?}';
    protected $description = 'Post a message to a Facebook group';

    public function handle()
    {
        $groupId = $this->argument('group_id');
        $message = $this->argument('message');
        $image = $this->argument('image') ?? '';

        $scriptPath = storage_path('app/public/script/fb_post_to_group.js');

        $process = new Process(["node", $scriptPath, $groupId, $message, $image]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->info('Post published successfully!');
    }
}
