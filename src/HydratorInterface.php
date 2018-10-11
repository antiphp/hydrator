<?php
declare(strict_types=1);

namespace Antiphp\Hydrator;

interface HydratorInterface
{
    public function hydrate(array $data, $object);
    public function extract($object): array;
}
