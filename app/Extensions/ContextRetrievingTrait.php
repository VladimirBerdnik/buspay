<?php

namespace App\Extensions;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

/**
 * Trait that allows to retrieve protected properties slice.
 */
trait ContextRetrievingTrait
{
    /**
     * Returns protected attributes context.
     *
     * @return mixed[]
     *
     * @throws ReflectionException
     */
    public function getContext(): array
    {
        $result = [];

        $class = new ReflectionClass(static::class);
        $protectedProperties = $class->getProperties(ReflectionProperty::IS_PROTECTED);

        foreach ($protectedProperties as $protectedProperty) {
            $propertyName = $protectedProperty->name;
            $result[$propertyName] = $this->{$propertyName};
        }

        return $result;
    }
}
