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
use phpDocumentor\Reflection\DocBlock\Tags\TemplateCovariant;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Types\Context;
use phpDocumentor\Reflection\Types\Object_;
use phpDocumentor\Reflection\Types\String_;

final class TemplateCovariantFactoryTest extends TagFactoryTestCase
{
    /**
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\Factory\TemplateCovariantFactory::__construct
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\Factory\TemplateCovariantFactory::create
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\Factory\TemplateCovariantFactory::supports
     * @dataProvider templateCovariantInputProvider
     */
    public function testTemplateCovariantIsCreated(string $input, Tag $expected): void
    {
        $ast = $this->parseTag($input);
        $factory = new TemplateCovariantFactory($this->giveTypeResolver(), $this->givenDescriptionFactory());
        $context = new Context('global');

        self::assertTrue($factory->supports($ast, $context));
        self::assertEquals(
            $expected,
            $factory->create($ast, $context)
        );
    }

    /**
     * @return array<int, array<int, string|TemplateCovariant>>
     */
    public function templateCovariantInputProvider(): array
    {
        return [
            [
                '@template-covariant string',
                new TemplateCovariant(
                    new String_(),
                    new Description('')
                ),
            ],
            [
                '@template-covariant SomeClass Description',
                new TemplateCovariant(
                    new Object_(new Fqsen('\SomeClass')),
                    new Description('Description')
                ),
            ],
            [
                '@template-covariant SomeClass',
                new TemplateCovariant(
                    new Object_(new Fqsen('\SomeClass')),
                    new Description('')
                ),
            ],
        ];
    }
}
