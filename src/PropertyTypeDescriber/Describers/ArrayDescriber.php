<?php

declare(strict_types=1);

namespace Kr0lik\DtoToSwagger\PropertyTypeDescriber\Describers;

use InvalidArgumentException;
use Kr0lik\DtoToSwagger\Helper\Util;
use Kr0lik\DtoToSwagger\PropertyTypeDescriber\PropertyTypeDescriber;
use Kr0lik\DtoToSwagger\PropertyTypeDescriber\PropertyTypeDescriberInterface;
use OpenApi\Annotations\AdditionalProperties;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\Schema;
use Symfony\Component\PropertyInfo\Type;

class ArrayDescriber implements PropertyTypeDescriberInterface
{
    public function __construct(
        private PropertyTypeDescriber $propertyDescriber,
    ) {}

    /**
     * @param array<string, mixed> $context
     *
     * @throws InvalidArgumentException
     */
    public function describe(Schema $property, array $context = [], Type ...$types): void
    {
        $type = $types[0]->getCollectionValueTypes()[0] ?? null;

        if (null === $type) {
            return;
        }

        $key = $types[0]->getCollectionKeyTypes()[0] ?? null;

        if ($key?->getBuiltinType() === 'string') {
            $property->type = 'object';
            /** @var AdditionalProperties $property */
            $property = Util::getChild($property, AdditionalProperties::class);
        } else {
            $property->type = 'array';
            /** @var Items $property */
            $property = Util::getChild($property, Items::class);
        }

        $this->propertyDescriber->describe($property, $context, $type);
    }

    public function supports(Type ...$types): bool
    {
        return 1 === count($types) && $types[0]->isCollection();
    }
}
