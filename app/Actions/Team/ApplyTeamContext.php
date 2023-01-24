<?php

namespace App\Actions\Team;

use App\Models\Team;
use Closure;
use Illuminate\Pipeline\Pipeline;

class ApplyTeamContext
{
    public function __construct(private ?Pipeline $pipeline = null)
    {
        $this->pipeline ??= new Pipeline(app());
    }

    public function handle(Team $team, Closure $callback)
    {
        return $this->pipeline->send($team)
            ->through([
                Context\SetTeamDomainAsAppUrl::class,
                $callback,
            ])
            ->thenReturn();
    }
}
