<?php

namespace PHPNomad\GraphQL\Interfaces;

interface TypeDefinition
{
    /**
     * SDL string for this type (and optional `extend type Query/Mutation` blocks).
     * Example: "type Book { id: ID! title: String! }\nextend type Query { book(id: ID!): Book }"
     */
    public function getSdl(): string;

    /**
     * Resolver map: ['TypeName' => ['fieldName' => FieldResolver::class]]
     * Only fields needing custom logic require entries. All other fields
     * use webonyx's default property resolution.
     */
    public function getResolvers(): array;
}
