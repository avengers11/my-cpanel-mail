<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AmazonCardAddJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
    */
    public $amazon_id;
    public $name;
    public $card;
    public $month;
    public $year;
    public function __construct($amazon_id, $name, $card, $month, $year)
    {
        $this->amazon_id = $amazon_id;
        $this->name = $name;
        $this->card = $card;
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $amazon_id = $this->amazon_id;
        $name = $this->name;
        $card = $this->card;
        $month = $this->month;
        $year = $this->year;

        Log::info("$name - $card - $month - $year - $amazon_id");

        return ;

        $phpPath = 'E:/Applications/laragon/bin/php/php8.2/php.exe';
        $artisanPath = 'E:/Applications/laragon/www/Laravel/63_cPanel_mail/artisan';
        $command = "$phpPath \"$artisanPath\" app:amazon-card-add \"$name\" \"$card\" \"$month\" \"$year\" \"$amazon_id\" 2>&1";
        shell_exec($command);
    }
}
