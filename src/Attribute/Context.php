<?php

declare(strict_types=1);

namespace Kr0lik\DtoToSwagger\Attribute;

use Attribute;
use JsonSerializable;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Context implements JsonSerializable
{
    /**
     * @param string[]|null $enum
     */
    public function __construct(
        public readonly ?string $format = null,
        public readonly ?string $pattern = null,
        public readonly ?array $enum = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return array_filter(get_object_vars($this), static fn (mixed $var): bool => null !== $var);
    }
}
