<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\Http\Middleware;

use Asseco\Multitenancy\Traits\UsesTenantModel;
use Closure;
use Exception;

class NeedsTenant
{
    use UsesTenantModel;

    public function handle($request, Closure $next)
    {
        if (!$this->getTenantModel()::checkCurrent()) {
            throw new Exception('The request expected a current tenant but none was set.');
        }

        return $next($request);
    }
}
