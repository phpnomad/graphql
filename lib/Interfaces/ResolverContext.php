<?php

namespace PHPNomad\GraphQL\Interfaces;

use PHPNomad\Http\Interfaces\Request;

interface ResolverContext
{
    public function getRequest(): Request;
}
