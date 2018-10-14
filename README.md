# README

In need of an easy and fast hydrator I had issues with the 
most common hydrators so I build my own. No dependencies - only
if you want to.

[![Build Status](https://travis-ci.org/antiphp/hydrator.svg?branch=master)](https://travis-ci.org/antiphp/hydrator)

# Hydrate

    $hydrator = new SetGetHydrator();
    $object = $hydrator->hydrate(['foo_bar' => 123], new Object());
    echo $object->getFooBar(); // 123

Considering `Object` to have `setFooBar($value)` and `getFoo()`
    
# Extract

    $object = new Object();
    $object->setFooBar(123);
    
    $hydrator = new SetGetHydrator();
    $data = $hydrator->extract($object);
    
    echo $data['foo_bar']; // 123


# Why not ...

## `zendframework/zend-hydrate`

This package's `ClassMethods` hydrator requires `zendframework/zend-filter` which
requires `zendframework/zend-servicemanager` which is annoying overhead. 


## `ocramius/generated-hydrator`

This package requires nikic/php-parser in an older version which is not 
compatible to `phpstan/phpstan`:`^0.10.*`. I don't want to be blocked by dependencies.


## `samdark/hydrator`

This package requires me to define a property mapping which
I don't want to define.


## `sylius-labs/association-hydrator`

You know, Doctrine.