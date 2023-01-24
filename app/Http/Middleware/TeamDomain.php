<?php

namespace App\Http\Middleware;

use App\Models\Team;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TeamDomain
{
    public function handle(Request $request, Closure $next)
    {
        /** @var Team $team */
        $team = Team::query()
            ->where('domain', $request->host())
            ->firstOrFail();

        if ($request->user() && ! $request->user()->belongsToTeam($team)) {
            throw new NotFoundHttpException();
        }

        if (! $request->session()->has('team_id')) {
            $request->session()->put('team_id' , $team->getKey());
        }

        if ($request->session()->get('team_id') != $team->getKey()) {
            throw new NotFoundHttpException();
        }

        $request->attributes->set('team', $team);

        URL::forceScheme('https');

        return $team->withinContext(fn () => $next($request));
    }
}
