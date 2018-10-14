<?php
/** @noinspection ALL */
declare(strict_types=1);

namespace Antiphp\Hydrator\Tests\Unit;

use Antiphp\Hydrator\SetGetHydrator;
use PHPUnit\Framework\TestCase;

class SetGetHydratorTest extends TestCase
{
    /**
     * @dataProvider provideValidScenarios
     *
     * @param string $message
     * @param string $hydrateKey
     * @param string $extractKey
     * @param $object
     */
    public function testSuccess(string $message, string $hydrateKey, string $extractKey, $object)
    {
        $hydrator = new SetGetHydrator();
        $object = $hydrator->hydrate([$hydrateKey => 123], $object);
        $data = $hydrator->extract($object);

        $this->assertArrayHasKey($extractKey, $data, $message . "; got only [" . implode(", ", array_keys($data)) . "]");
        $this->assertEquals(123, $data[$extractKey]);
    }

    /**
     * @dataProvider provideSkipSetterScenarios
     *
     * @param string $name
     * @param $object
     */
    public function testInvalidSetter(string $name, $object)
    {
        $hydrator = new SetGetHydrator();
        $object = $hydrator->hydrate(['foo_bar' => 123], $object);
        $data = $hydrator->extract($object);

        $this->assertArrayHasKey('foo_bar', $data, $name);
        $this->assertNotEquals(123, $data['foo_bar'], $name);
    }

    /**
     * @dataProvider provideSkipGetterScenarios
     * @param string $name
     * @param $object
     */
    public function testInvalidGetter(string $name, $object)
    {
        $hydrator = new SetGetHydrator();
        $object = $hydrator->hydrate(['foo_bar' => 123], $object);
        $data = $hydrator->extract($object);

        $this->assertInternalType('array', $data, $name);
        $this->assertEmpty($data, $name);
    }

    public function provideValidScenarios(): array
    {
        return [
            [
                'Simple key',
                'foobar',
                'foobar',
                (function() {
                    return new class {
                        public $value;
                        public function setFoobar($value) {
                            $this->value = $value;
                        }
                        public function getFoobar() {
                            return $this->value;
                        }
                    };
                })()
            ],
            [
                'Snake-case key',
                'foo_bar',
                'foo_bar',
                (function() {
                    return new class {
                        public $foobar;
                        public function setFooBar($foobar) {
                            $this->foobar = $foobar;
                        }
                        public function getFooBar() {
                            return $this->foobar;
                        }
                    };
                })()
            ],
            [
                'Camel-case key',
                'fooBar',
                'foo_bar',
                (function() {
                    return new class {
                        public $value;
                        public function setFooBar($value) {
                            $this->value = $value;
                        }
                        public function getFooBar() {
                            return $this->value;
                        }
                    };
                })()
            ],
            [
                'Edge-case: Setter has optional second argument',
                'foo_bar',
                'foo_bar',
                (function() {
                    return new class {
                        public $value;
                        public function setFooBar($value, $optional = true) {
                            $this->value = $value;
                        }
                        public function getFooBar() {
                            return $this->value;
                        }
                    };
                })()
            ],
            [
                'Edge-case: Getter has optional argument',
                'foo_bar',
                'foo_bar',
                (function() {
                    return new class {
                        public $value;
                        public function setFooBar($value) {
                            $this->value = $value;
                        }
                        public function getFooBar($optional = true) {
                            return $this->value;
                        }
                    };
                })()
            ],
        ];
    }

    public function provideSkipSetterScenarios(): array
    {
        // expected to handle "foo_bar"
        return [
            [
                'No setter method implemented',
                new class {
                    public $value = 234;
                    public function getFooBar() {
                        return $this->value;
                    }
                }
            ],
            [
                'Incompatible setter #1: No argument',
                new class {
                    public $value = 234;
                    public function setFooBar() {
                    }
                    public function getFooBar() {
                        return $this->value;
                    }
                }
            ],
            [
                'Incompatible setter #2: Too many required arguments',
                new class {
                    public $value = 234;
                    public function setFooBar($value, $value2) {
                        $this->value = $value;
                    }
                    public function getFooBar() {
                        return $this->value;
                    }
                }
            ],
        ];
    }
    public function provideSkipGetterScenarios(): array
    {
        // expected to handle "foo_bar"
        return [
            [
                'No getter method implemented',
                new class {
                    public $value = 234;
                    public function setFooBar($value) {
                        $this->value = $value;
                    }
                }
            ],
            [
                'Incompatible getter #1: Required argument',
                new class {
                    public $value = 234;
                    public function setFooBar($value) {
                        $this->value = $value;
                    }
                    public function getFooBar($required) {
                        return $this->value;
                    }
                }
            ],
            [
                'Incompatible getter #2: Not public',
                new class {
                    public $value = 234;
                    public function setFooBar($value) {
                        $this->value = $value;
                    }
                    protected function getFooBar() {
                        return $this->value;
                    }
                }
            ],
        ];
    }
}

