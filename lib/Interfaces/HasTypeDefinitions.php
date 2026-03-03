<?php

namespace PHPNomad\GraphQL\Interfaces;

interface HasTypeDefinitions
{
    /**
     * @return class-string<TypeDefinition>[]
     */
    public function getTypeDefinitions(): array;
}
