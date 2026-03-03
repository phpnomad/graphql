<?php

namespace PHPNomad\GraphQL\Interfaces;

interface FieldResolver
{
    public function resolve(mixed $rootValue, array $args, ResolverContext $context): mixed;
}
