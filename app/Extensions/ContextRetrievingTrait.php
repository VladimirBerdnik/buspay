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

        $class = new ReflectionClass(__CLASS__);
        $privateProperties = $class->getProperties(ReflectionProperty::IS_PRIVATE);

        foreach ($privateProperties as $privateProperty) {
            $propertyName = $privateProperty->name;
            $result[$propertyName] = $this->{$propertyName};
        }

        return $result;
    }
}
