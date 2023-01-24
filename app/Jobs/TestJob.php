<?php

namespace App\Jobs;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Team $team)
    {
        //
    }

    public function handle()
    {
        logger('Outside Team Context (Before)', [
            'url generated with url() helper' => url('/'),
            'url generated with route() helper' => route('dashboard'),
        ]);

        $this->team->withinContext(function () {
            logger('Within Team Context', [
                'url generated with url() helper' => url('/'),
                'url generated with route() helper' => route('dashboard'),
            ]);
        });

        logger('Outside Team Context (After)', [
            'url generated with url() helper' => url('/'),
            'url generated with route() helper' => route('dashboard'),
        ]);
    }
}
