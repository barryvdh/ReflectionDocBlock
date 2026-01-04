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
use phpDocumentor\Reflection\DocBlock\Tags\Implements_;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\PseudoTypes\Generic;
use phpDocumentor\Reflection\Types\Context;
use phpDocumentor\Reflection\Types\Object_;

final class ImplementsFactoryTest extends TagFactoryTestCase
{
    /**
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\Factory\ImplementsFactory::__construct
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\Factory\ImplementsFactory::create
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\Factory\ImplementsFactory::supports
     */
    public function testImplementsIsCreated(): void
    {
        $ast = $this->parseTag('@implements SomeClass<OtherType>');
        $factory = new ImplementsFactory($this->giveTypeResolver(), $this->givenDescriptionFactory());
        $context = new Context('global');

        self::assertTrue($factory->supports($ast, $context));
        self::assertEquals(
            new Implements_(
                new Generic(new Fqsen('\\SomeClass'), [new Object_(new Fqsen('\\OtherType'))]),
                new Description('')
            ),
            $factory->create($ast, $context)
        );
    }
}
