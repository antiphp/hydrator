<?php
declare(strict_types=1);

namespace Antiphp\Hydrator\Adapter;

use Antiphp\Hydrator\HydratorInterface;
use Zend\Hydrator\HydratorInterface as ZendHydratorInterface;

class ZendHydrator implements ZendHydratorInterface
{
    private $hydrator;

    public function __construct(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    public function extract($object)
    {
        return $this->hydrator->extract($object);
    }

    public function hydrate(array $data, $object)
    {
        return $this->hydrator->hydrate($data, $object);
    }
}
