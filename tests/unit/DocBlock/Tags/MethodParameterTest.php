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

namespace phpDocumentor\Reflection\DocBlock\Tags;

use Mockery as m;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Float_;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\Nullable;
use phpDocumentor\Reflection\Types\Object_;
use phpDocumentor\Reflection\Types\String_;
use PHPUnit\Framework\TestCase;
use stdClass;

use function sprintf;

/**
 * @coversDefaultClass \phpDocumentor\Reflection\DocBlock\Tags\Method
 * @covers ::<private>
 */
class MethodParameterTest extends TestCase
{
    /**
     * Call Mockery::close after each test.
     */
    public function tearDown(): void
    {
        m::close();
    }

    /** @return array<array{0: Type, 1: mixed, 2: string}> */
    public function collectionDefaultValuesProvider(): array
    {
        return [
            [new String_(), '1', '\'1\''],
            [new Integer(), 1, '1'],
            [new Boolean(), true, 'true'],
            [new Float_(), 1.23, '1.23'],
            [new Array_(), [1, '2', true], '[1,\'2\',true]'],
            [new Array_(), [[1, 2], '2', true], '[[1,2],\'2\',true]'],
            [new Nullable(new Float_()), null, 'null'],
            [new Nullable(new Float_()), 1.23, '1.23'],
            [new Object_(new Fqsen('\\stdClass')), new stdClass(), 'new stdClass()'],
        ];
    }

    /**
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\MethodParameter::__construct
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\MethodParameter::getDefaultValue()
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\MethodParameter::__toString
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\Formatter\PassthroughFormatter
     *
     * @param mixed $defaultValue
     *
     * @dataProvider collectionDefaultValuesProvider
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\BaseTag::render
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\BaseTag::getName
     */
    public function testIfTagCanBeRenderedUsingMethodParameterWithDefaultValue(
        Type $type,
        $defaultValue,
        string $defaultValueStr
    ): void {
        $fixture = new MethodParameter('argument', $type, false, false, $defaultValue);

        $this->assertSame(
            sprintf('%s $argument = %s', $type, $defaultValueStr),
            (string) $fixture
        );
        $this->assertSame(
            $defaultValueStr,
            $fixture->getDefaultValue()
        );
    }

    /**
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\MethodParameter::__construct
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\MethodParameter::getDefaultValue()
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\MethodParameter::__toString
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\Formatter\PassthroughFormatter
     *
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\BaseTag::render
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\BaseTag::getName
     */
    public function testIfTagCanBeRenderedUsingMethodParameterWithNoDefaultValue(): void
    {
        $fixture = new MethodParameter('argument', new Float_());

        $this->assertSame(
            'float $argument',
            (string) $fixture
        );
    }
}
