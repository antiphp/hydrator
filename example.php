<?php

namespace Antiphp\Hydrator;

require __DIR__ . '/vendor/autoload.php';

$example = new class {
    private $id;
    private $fooBar;

    public function setId($id) {
        $this->id = $id;
    }
    public function getId() {
        return $this->id;
    }

    public function setFooBar($fooBar) {
        $this->fooBar = $fooBar;
    }
    public function getFooBar() {
        return $this->fooBar;
    }
};

// hydrate
$hydrator = new SetGetHydrator();
$object = $hydrator->hydrate(['id' => 123, 'foo_bar' => 'something'], $example);
print_r($object);

// extract
$data = $hydrator->extract($example);
print_r($data);

