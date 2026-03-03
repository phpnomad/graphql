<?php

namespace PHPNomad\GraphQL\Interfaces;

interface GraphQLStrategy
{
    public function registerTypeDefinition(callable $definitionGetter): void;

    public function execute(string $query, array $variables, ResolverContext $context): array;
}
