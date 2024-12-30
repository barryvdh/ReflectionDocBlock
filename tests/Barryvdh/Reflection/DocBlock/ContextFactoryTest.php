<?php
declare(strict_types=1);
/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link      http://phpdoc.org
 */

namespace Barryvdh\Reflection\DocBlock;

// Added imports on purpose as mock for the unit tests, please do not remove.
use Barryvdh\Reflection\DocBlock\Tag as m, Barryvdh;
use Barryvdh\Reflection\DocBlock;
use Barryvdh\Reflection\DocBlock\Tag;
use PHPUnit\Framework\TestCase;

// yes, the slash is part of the test
use PHPUnit\Framework\{
    Assert,
    Exception as e
};
use \ReflectionClass;
use stdClass;

/**
 * @coversDefaultClass \phpDocumentor\Reflection\Types\ContextFactory
 * @covers ::<private>
 */
class ContextFactoryTest extends TestCase
{
    /**
     * @covers ::createFromReflector
     * @covers ::createForNamespace
     * @uses Barryvdh\Reflection\DocBlock\Context
     */
    public function testReadsNamespaceFromClassReflection(): void
    {
        $fixture = new ContextFactory();
        $context = $fixture->createFromReflector(new ReflectionClass($this));

        $this->assertSame(__NAMESPACE__, $context->getNamespace());
    }

    /**
     * @covers ::createFromReflector
     * @covers ::createForNamespace
     * @uses Barryvdh\Reflection\DocBlock\Context
     */
    public function testReadsAliasesFromClassReflection(): void
    {
        $fixture = new ContextFactory();
        $context = $fixture->createFromReflector(new ReflectionClass($this));

        $this->assertNamespaceAliasesFrom($context);
    }

    /**
     * @covers ::createForNamespace
     * @uses Barryvdh\Reflection\DocBlock\Context
     */
    public function testReadsNamespaceFromProvidedNamespaceAndContent(): void
    {
        $fixture = new ContextFactory();
        $context = $fixture->createForNamespace(__NAMESPACE__, file_get_contents(__FILE__));

        $this->assertSame(__NAMESPACE__, $context->getNamespace());
    }

    /**
     * @covers ::createForNamespace
     * @uses Barryvdh\Reflection\DocBlock\Context
     */
    public function testReadsAliasesFromProvidedNamespaceAndContent(): void
    {
        $fixture = new ContextFactory();
        $context = $fixture->createForNamespace(__NAMESPACE__, file_get_contents(__FILE__));

        $this->assertNamespaceAliasesFrom($context);
    }

    /**
     * @covers ::createForNamespace
     * @uses Barryvdh\Reflection\DocBlock\Context
     */
    public function testTraitUseIsNotDetectedAsNamespaceUse(): void
    {
        $php = '<?php declare(strict_types=1);
                namespace Foo;

                trait FooTrait {}

                class FooClass {
                    use FooTrait;
                }
            ';

        $fixture = new ContextFactory();
        $context = $fixture->createForNamespace('Foo', $php);

        $this->assertSame([], $context->getNamespaceAliases());
    }

    /**
     * @covers ::createForNamespace
     * @uses Barryvdh\Reflection\DocBlock\Context
     */
    public function testAllOpeningBracesAreCheckedWhenSearchingForEndOfClass(): void
    {
        $php = '<?php declare(strict_types=1);
                namespace Foo;

                trait FooTrait {}
                trait BarTrait {}

                class FooClass {
                    use FooTrait;

                    public function bar()
                    {
                        echo "{$baz}";
                        echo "${baz}";
                    }
                }

                class BarClass {
                    use BarTrait;

                    public function bar()
                    {
                        echo "{$baz}";
                        echo "${baz}";
                    }
                }
            ';

        $fixture = new ContextFactory();
        $context = $fixture->createForNamespace('Foo', $php);

        $this->assertSame([], $context->getNamespaceAliases());
    }

    /**
     * @covers ::createForNamespace
     * @uses Barryvdh\Reflection\DocBlock\Context
     */
    public function testTraitContainsClosureWithUseStatement(): void
    {
        $php = '<?php declare(strict_types=1);
                namespace Foo;

                trait FooTrait {
                    protected function check(array $data, string $key) : void
                    {
                        array_walk($data, function(&$item) use ($key) {
                            // update item based on the key
                        });
                    }
                }

                class FooClass {
                    use FooTrait;
                }
            ';

        $fixture = new ContextFactory();
        $context = $fixture->createForNamespace('Foo', $php);

        $this->assertSame([], $context->getNamespaceAliases());
    }

    /**
     * @covers ::createFromReflector
     */
    public function testEmptyFileName(): void
    {
        $fixture = new ContextFactory();
        $context = $fixture->createFromReflector(new ReflectionClass(stdClass::class));

        $this->assertSame([], $context->getNamespaceAliases());
    }

    /**
     * @covers ::createFromReflector
     */
    public function testEvalDClass(): void
    {
        eval(
        <<<PHP
namespace Foo;

class Bar
{
}
PHP
        );
        $fixture = new ContextFactory();
        $context = $fixture->createFromReflector(new ReflectionClass('Foo\Bar'));

        $this->assertSame([], $context->getNamespaceAliases());
    }

    public function assertNamespaceAliasesFrom(Context $context)
    {
        $expected = [
            'm' => '\\' . m::class,
            'DocBlock' => '\\' . DocBlock::class,
            'Tag' => '\\' . Tag::class,
            'Barryvdh' => '\\' . 'Barryvdh',
            'TestCase' => '\\' . TestCase::class,
            'Assert' => '\\' . Assert::class,
            'e' => '\\' . e::class,
            ReflectionClass::class => '\\' . ReflectionClass::class,
            \stdClass::class => '\\' . \stdClass::class,
        ];

        $actual = $context->getNamespaceAliases();

        // sort so that order differences don't break it
        asort($expected);
        asort($actual);

        $this->assertSame($expected, $actual);
    }
}
