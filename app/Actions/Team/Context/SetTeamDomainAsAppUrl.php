<?php

namespace App\Actions\Team\Context;

use App\Models\Team;
use Closure;
use Illuminate\Support\Facades\URL;

class SetTeamDomainAsAppUrl
{
    public function __invoke(Team $team, Closure $next)
    {
        $oldUrl = config('app.url');
        $scheme = parse_url($oldUrl)['scheme'] ?? 'https';

        config()->set('app.url', $url = $scheme . '://' . $team->domain);
        URL::forceRootUrl($url);

        try {
            return $next($team);
        } finally {
            config()->set('app.url', $oldUrl);
            URL::forceRootUrl($oldUrl);
        }
    }
}
