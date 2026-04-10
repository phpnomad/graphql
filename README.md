# phpnomad/graphql

[![Latest Version](https://img.shields.io/packagist/v/phpnomad/graphql.svg)](https://packagist.org/packages/phpnomad/graphql) [![Total Downloads](https://img.shields.io/packagist/dt/phpnomad/graphql.svg)](https://packagist.org/packages/phpnomad/graphql) [![PHP Version](https://img.shields.io/packagist/php-v/phpnomad/graphql.svg)](https://packagist.org/packages/phpnomad/graphql) [![License](https://img.shields.io/packagist/l/phpnomad/graphql.svg)](https://packagist.org/packages/phpnomad/graphql)

`phpnomad/graphql` is an engine-agnostic GraphQL abstraction for [PHPNomad](https://phpnomad.com). It defines the contracts your application writes against (`TypeDefinition`, `FieldResolver`, `GraphQLStrategy`, and `ResolverContext`) so that type definitions and resolvers stay portable across whichever GraphQL engine you plug in underneath. Your schema code depends only on these interfaces, which keeps it decoupled from the runtime library and easy to test in isolation.

The package itself ships five interfaces and a single exception class, not a runtime. The recommended concrete strategy lives in [`phpnomad/webonyx-integration`](https://packagist.org/packages/phpnomad/webonyx-integration), which adapts these interfaces to [`webonyx/graphql-php`](https://github.com/webonyx/graphql-php). Install both together when you want a working GraphQL layer.

## Installation

```bash
composer require phpnomad/graphql
```

For a concrete engine, also pull in the webonyx strategy:

```bash
composer require phpnomad/webonyx-integration
```

## Quick Start

A `TypeDefinition` returns an SDL string and a resolver map. Only fields that need custom logic require a resolver entry. All other fields fall back to the engine's default property resolution.

```php
<?php

namespace MyApp\GraphQL\Types;

use MyApp\GraphQL\Resolvers\BookByIdResolver;
use PHPNomad\GraphQL\Interfaces\TypeDefinition;

class BookType implements TypeDefinition
{
    public function getSdl(): string
    {
        return <<<SDL
        type Book {
            id: ID!
            title: String!
            author: String!
        }

        extend type Query {
            book(id: ID!): Book
        }
        SDL;
    }

    public function getResolvers(): array
    {
        return [
            'Query' => [
                'book' => BookByIdResolver::class,
            ],
        ];
    }
}
```

A `FieldResolver` is a single-method class that receives the root value, the field arguments, and a `ResolverContext` carrying the PHPNomad HTTP request.

```php
<?php

namespace MyApp\GraphQL\Resolvers;

use MyApp\Books\BookRepository;
use PHPNomad\GraphQL\Interfaces\FieldResolver;
use PHPNomad\GraphQL\Interfaces\ResolverContext;

class BookByIdResolver implements FieldResolver
{
    public function __construct(protected BookRepository $books)
    {
    }

    public function resolve(mixed $rootValue, array $args, ResolverContext $context): mixed
    {
        return $this->books->findById((string) $args['id']);
    }
}
```

Register each `TypeDefinition` with the `GraphQLStrategy` supplied by the integration package, then call `execute()` with a query string, a variables array, and the context. Because the resolver map references class strings, the strategy can hydrate resolvers through the DI container when they run.

## Key Concepts

- `TypeDefinition`: SDL for a type plus a resolver map for fields that need custom logic
- `FieldResolver`: a single `resolve()` method taking the root value, field arguments, and a `ResolverContext`
- `GraphQLStrategy`: the engine adapter responsible for registering type definitions and executing queries
- `ResolverContext`: carries the PHPNomad HTTP `Request` through to resolvers so they can reach request-scoped state
- `HasTypeDefinitions`: marker interface for classes that group a set of `TypeDefinition` class strings for registration
- `GraphQLException`: base exception the package throws when something goes wrong in the GraphQL layer

## Documentation

Full PHPNomad documentation lives at [phpnomad.com](https://phpnomad.com). For the concrete engine that pairs with this package, see [`phpnomad/webonyx-integration`](https://packagist.org/packages/phpnomad/webonyx-integration) and the upstream [webonyx/graphql-php](https://github.com/webonyx/graphql-php) documentation.

## License

MIT, see [LICENSE](LICENSE) for the full text.
