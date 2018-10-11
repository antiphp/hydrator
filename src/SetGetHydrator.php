<?php
declare(strict_types=1);

namespace Antiphp\Hydrator;

use Nayjest\StrCaseConverter\Str;

/**
 * Hydrator using set* and get* methods. Incompatible methods will be skipped.
 */
class SetGetHydrator implements HydratorInterface
{
    public function hydrate(array $data, $object)
    {
        $reflectionObject = new \ReflectionObject($object);
        foreach ($data as $key => $value) {
            $methodName = 'set' . ucfirst($this->convertBtoA($key));
            if (!$reflectionObject->hasMethod($methodName)) {
                continue;
            }
            $reflectionMethod = $reflectionObject->getMethod($methodName);
            if (!$reflectionMethod->isPublic()) {
                continue;
            }
            if ($reflectionMethod->getNumberOfRequiredParameters() > 1) {
                continue;
            }
            if ($reflectionMethod->getNumberOfParameters() < 1) {
                continue;
            }
            $reflectionMethod->invoke($object, $value);
        }
    }

    public function extract($object): array
    {
        $data = [];
        $reflectionObject = new \ReflectionObject($object);
        foreach ($reflectionObject->getMethods(\ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
            $methodName = $reflectionMethod->getName();
            if (strpos($methodName, 'get') !== 0) {
                continue;
            }
            if (!$reflectionMethod->isPublic()) {
                continue;
            }
            if ($reflectionMethod->getNumberOfRequiredParameters() !== 0) {
                continue;
            }
            $keyName = substr($methodName, 3);
            $keyName = $this->convertAtoB($keyName);
            $data[$keyName] = $reflectionMethod->invoke($object);
        }
        return $data;
    }

    protected function convertAtoB($a): string
    {
        return Str::toSnakeCase($a);
    }

    protected function convertBtoA($b): string
    {
        return Str::toCamelCase($b);
    }
}
