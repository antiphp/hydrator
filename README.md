# README

In need of an easy and fast hydrator I had issues with the 
most common hydrators so I build my own. No dependencies - only
if you want to.

# Hydrate

    $hydrator = new SetGetHydrator();
    $options  = $hydrator->hydrate(['foo' => 'bar'], new Example());
    echo $options->getFoo(); // bar

Considering `Example` to have `setFoo` (for hydration) and `getFoo` (for example usage) implemented.
    
# Extract

    $foobar   = new Example();
    $foobar->setFoo('bar');
    
    $hydrator = new SetGetHydrator();
    $options  = $hydrator->extract($foobar);
    
    echo $options['foo']; // bar

# More

There is also a wrapper to fit `Zend\Hydrator\HydratorInterface`:

    $hydrator = new ZendHydrator(new SetGetHydrator());
    assert($hydrator instanceof Zend\Hydrator\HydratorInterface); // true


# Why not ...

## `zendframework/zend-hydrate`

This package's `ClassMethods` hydrator requires `zendframework/zend-filter` which
requires `zendframework/zend-servicemanager` which is annoying overhead. 


## `ocramius/generated-hydrator`

This package requires nikic/php-parser in an older version which is not 
compatible to `phpstan/phpstan`:`^0.10.*`


## `samdark/hydrator`

This package requires me to define a property mapping which
I don't want to define.


## `sylius-labs/association-hydrator`

You know, Doctrine.