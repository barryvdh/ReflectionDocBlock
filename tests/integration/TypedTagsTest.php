<?php

declare(strict_types=1);

namespace integration;

use phpDocumentor\Reflection\DocBlock\Tags\InvalidTag;
use phpDocumentor\Reflection\DocBlock\Tags\Method;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\PseudoTypes\ArrayShape;
use phpDocumentor\Reflection\PseudoTypes\ArrayShapeItem;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Callable_;
use phpDocumentor\Reflection\Types\CallableParameter;
use phpDocumentor\Reflection\Types\Collection;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\Nullable;
use phpDocumentor\Reflection\Types\Object_;
use phpDocumentor\Reflection\Types\String_;
use phpDocumentor\Reflection\Types\Void_;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
final class TypedTagsTest extends TestCase
{
    /** @dataProvider typeProvider */
    public function testParamFormats(string $type, Type $expectedType): void
    {
        $docblock = <<<DOCBLOCK
/**
 * @param $type \$param
 */
DOCBLOCK;

        $factory = DocBlockFactory::createInstance();
        $phpdoc = $factory->create($docblock);

        $this->assertInstanceOf(Param::class, $phpdoc->getTags()[0]);
        $this->assertEquals($expectedType, $phpdoc->getTags()[0]->getType());
    }

    /** @dataProvider typeProvider */
    public function testVarFormats(string $type, Type $expectedType): void
    {
        $docblock = <<<DOCBLOCK
/**
 * @var $type \$param
 */
DOCBLOCK;

        $factory = DocBlockFactory::createInstance();
        $phpdoc = $factory->create($docblock);

        $this->assertInstanceOf(Var_::class, $phpdoc->getTags()[0]);
        $this->assertEquals($expectedType, $phpdoc->getTags()[0]->getType());
    }

    /** @dataProvider typeProvider */
    public function testMethodFormats(string $type, Type $expectedType): void
    {
        $docblock = <<<DOCBLOCK
/**
 * @method $type setValue($type \$param)
 */
DOCBLOCK;

        $factory = DocBlockFactory::createInstance();
        $phpdoc = $factory->create($docblock);

        $this->assertInstanceOf(Method::class, $phpdoc->getTags()[0]);
        $this->assertEquals($expectedType, $phpdoc->getTags()[0]->getReturnType());
        $this->assertEquals($expectedType, $phpdoc->getTags()[0]->getParameters()[0]->getType());
    }

    /** @dataProvider invalidFormatsProvider */
    public function testInvalidParam(string $type): void
    {
        $docblock = <<<DOCBLOCK
/**
 * @param $type \$param
 */
DOCBLOCK;

        $factory = DocBlockFactory::createInstance();
        $phpdoc = $factory->create($docblock);

        $this->assertInstanceOf(InvalidTag::class, $phpdoc->getTags()[0]);
        $this->assertEquals("$type \$param", (string) $phpdoc->getTags()[0]);

    }

    /** @dataProvider invalidFormatsProvider */
    public function testInvalidMethod(string $type): void
    {
        $docblock = <<<DOCBLOCK
/**
 * @method $type setValue($type \$param)
 */
DOCBLOCK;

        $factory = DocBlockFactory::createInstance();
        $phpdoc = $factory->create($docblock);

        $this->assertInstanceOf(InvalidTag::class, $phpdoc->getTags()[0]);
        $this->assertEquals("$type setValue($type \$param)", (string) $phpdoc->getTags()[0]);

    }

    public static function typeProvider(): array
    {
        return [
            [
                'int',
                new Integer(),
            ],
            [
                'integer',
                new Integer(),
            ],
            [
                'string',
                new String_(),
            ],
            [
                'T',
                new Object_(new Fqsen('\\T')),
            ],
            [
                'array',
                new Array_(),
            ],
            [
                'int[]',
                new Array_(new Integer()),
            ],
            [
                'int[][]',
                new Array_(new Array_(new Integer())),
            ],
            [
                'array<int, string>',
                new Array_(new String_(), new Integer()),
            ],
            [
                'array{
                   foo: int,
                   bar?: ?string
                }',
                new ArrayShape(
                    new ArrayShapeItem('foo', new Integer(), false),
                    new ArrayShapeItem('bar', new Nullable(new String_()), true),

                )
            ],
            [
                'Collection<int, string>',
                new Collection(new Fqsen('\\Collection'), new String_(), new Integer()),
            ],
            [
                'Collection<TKey, Tvalue>',
                new Collection(new Fqsen('\\Collection'), new Object_(new Fqsen('\\Tvalue')), new Object_(new Fqsen('\\TKey'))),
            ],
            [
                'Collection<Tvalue>',
                new Collection(new Fqsen('\\Collection'), new Object_(new Fqsen('\\Tvalue'))),
            ],
            [
                'callable(int $foo, string $bar): void',
                new Callable_([new CallableParameter(new Integer(), 'foo'), new CallableParameter(new String_(), 'bar')], new Void_()),
            ],
        ];
    }

    public static function invalidFormatsProvider(): \Generator
    {
        foreach (['<', '{', '('] as $badChar) {
            yield "invalid format open $badChar" => ["array$badChar\\fooo"];
        }

        foreach (['>', '}', ')'] as $badChar) {
            yield "invalid format close $badChar" => ["array\\fooo$badChar"];
        }
    }
}
