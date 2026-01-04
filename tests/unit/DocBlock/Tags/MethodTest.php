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
use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\Exception\CannotCreateTag;
use phpDocumentor\Reflection\Types\Object_;
use phpDocumentor\Reflection\Types\String_;
use phpDocumentor\Reflection\Types\Void_;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \phpDocumentor\Reflection\DocBlock\Tags\Method
 * @covers ::<private>
 */
class MethodTest extends TestCase
{
    /**
     * Call Mockery::close after each test.
     */
    public function tearDown(): void
    {
        m::close();
    }

    /**
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\Method::__construct
     *
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\BaseTag::getName
     */
    public function testIfCorrectTagNameIsReturned(): void
    {
        $fixture = new Method('myMethod');

        $this->assertSame('method', $fixture->getName());
    }

    /**
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\Method::__construct
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\Method::isStatic
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\Method::__toString
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\Formatter\PassthroughFormatter
     * @uses   \phpDocumentor\Reflection\DocBlock\Description
     *
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\BaseTag::render
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\BaseTag::getName
     */
    public function testIfTagCanBeRenderedUsingDefaultFormatter(): void
    {
        $arguments = [
            new MethodParameter('argument1', new String_()),
            new MethodParameter('argument2', new Object_()),
        ];

        $fixture = new Method(
            'myMethod',
            $arguments,
            new Void_(),
            true,
            new Description('My Description')
        );

        $this->assertSame(
            '@method static void myMethod(string $argument1, object $argument2) My Description',
            $fixture->render()
        );
    }

    /**
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\Method::__construct
     * @uses   \phpDocumentor\Reflection\DocBlock\Description
     *
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\BaseTag::render
     */
    public function testIfTagCanBeRenderedUsingSpecificFormatter(): void
    {
        $fixture = new Method('myMethod');

        $formatter = m::mock(Formatter::class);
        $formatter->allows('format')->with($fixture)->andReturns('Rendered output');

        $this->assertSame('Rendered output', $fixture->render($formatter));
    }

    /**
     * @covers ::__construct
     * @covers ::getMethodName
     */
    public function testHasMethodName(): void
    {
        $expected = 'myMethod';

        $fixture = new Method($expected);

        $this->assertSame($expected, $fixture->getMethodName());
    }

    /**
     * @covers ::__construct
     * @covers ::getArguments
     */
    public function testHasArguments(): void
    {
        $arguments = [
            new MethodParameter('argument1', new String_()),
        ];

        $fixture = new Method('myMethod', $arguments);

        $this->assertSame($arguments, $fixture->getParameters());
    }

    /**
     * @covers ::__construct
     * @covers ::getReturnType
     */
    public function testHasReturnType(): void
    {
        $expected = new String_();

        $fixture = new Method('myMethod', [], $expected);

        $this->assertSame($expected, $fixture->getReturnType());
    }

    /**
     * @covers ::__construct
     * @covers ::getReturnType
     */
    public function testReturnTypeCanBeInferredAsVoid(): void
    {
        $fixture = new Method('myMethod', []);

        $this->assertEquals(new Void_(), $fixture->getReturnType());
    }

    /**
     * @covers ::__construct
     * @covers ::isStatic
     */
    public function testMethodCanBeStatic(): void
    {
        $expected = false;
        $fixture  = new Method('myMethod', [], null, $expected);
        $this->assertSame($expected, $fixture->isStatic());

        $expected = true;
        $fixture  = new Method('myMethod', [], null, $expected);
        $this->assertSame($expected, $fixture->isStatic());
    }

    /**
     * @uses   \phpDocumentor\Reflection\DocBlock\Description
     *
     * @covers ::__construct
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\BaseTag::getDescription
     */
    public function testHasDescription(): void
    {
        $expected = new Description('Description');

        $fixture = new Method('myMethod', [], null, false, $expected);

        $this->assertSame($expected, $fixture->getDescription());
    }

    /**
     * @uses   \phpDocumentor\Reflection\DocBlock\Description
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\Method::isStatic
     *
     * @covers ::__construct
     * @covers ::__toString
     */
    public function testStringRepresentationIsReturned(): void
    {
        $arguments = [
            new MethodParameter('argument1', new String_()),
            new MethodParameter('argument2', new Object_()),
        ];
        $fixture   = new Method('myMethod', $arguments, new Void_(), true, new Description('My Description'));

        $this->assertSame(
            'static void myMethod(string $argument1, object $argument2) My Description',
            (string) $fixture
        );
    }

    /**
     * @uses   \phpDocumentor\Reflection\DocBlock\Description
     * @uses   \phpDocumentor\Reflection\DocBlock\Tags\Method::isStatic
     *
     * @covers ::__construct
     * @covers ::__toString
     */
    public function testStringRepresentationIsReturnedWithoutDescription(): void
    {
        $fixture = new Method('myMethod', [], null, false, new Description(''));

        $this->assertSame(
            'void myMethod()',
            (string) $fixture
        );

        $arguments = [
            new MethodParameter('argument1', new String_()),
            new MethodParameter('argument2', new Object_()),
        ];
        $fixture   = new Method('myMethod', $arguments, new Void_(), true);

        $this->assertSame(
            'static void myMethod(string $argument1, object $argument2)',
            (string) $fixture
        );
    }

    /**
     * @covers ::__construct
     * @covers ::getReturnType
     */
    public function testReturnsReference(): void
    {
        $expected = new String_();

        $fixture = new Method('myMethod', [], $expected);

        $this->assertFalse($fixture->returnsReference());
    }

    /**
     * @covers ::create
     */
    public function testFactoryMethodThrows(): void
    {
        $this->expectException(CannotCreateTag::class);
        Method::create('');
    }
}
