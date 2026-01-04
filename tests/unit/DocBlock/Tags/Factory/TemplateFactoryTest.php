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

namespace phpDocumentor\Reflection\DocBlock\Tags\Factory;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\Tag;
use phpDocumentor\Reflection\DocBlock\Tags\Template;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Types\Context;
use phpDocumentor\Reflection\Types\Mixed_;
use phpDocumentor\Reflection\Types\Object_;

final class TemplateFactoryTest extends TagFactoryTestCase
{
    /**
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\Factory\TemplateFactory::__construct
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\Factory\TemplateFactory::create
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\Factory\TemplateFactory::supports
     * @dataProvider templateInputProvider
     */
    public function testTemplateIsCreated(string $input, Tag $expected): void
    {
        $ast = $this->parseTag($input);
        $factory = new TemplateFactory($this->giveTypeResolver(), $this->givenDescriptionFactory());
        $context = new Context('global');

        self::assertTrue($factory->supports($ast, $context));
        self::assertEquals(
            $expected,
            $factory->create($ast, $context)
        );
    }

    /**
     * @return array<int, array<int, string|Template>>
     */
    public function templateInputProvider(): array
    {
        return [
            [
                '@template T',
                new Template(
                    'T',
                    new Mixed_(),
                    new Mixed_(),
                    new Description('')
                ),
            ],
            [
                '@template T of SomeClass Description',
                new Template(
                    'T',
                    new Object_(new Fqsen('\SomeClass')),
                    new Mixed_(),
                    new Description('Description')
                ),
            ],
            [
                '@template T of SomeClass = Default',
                new Template(
                    'T',
                    new Object_(new Fqsen('\SomeClass')),
                    new Object_(new Fqsen('\Default')),
                    new Description('')
                ),
            ],
        ];
    }
}
