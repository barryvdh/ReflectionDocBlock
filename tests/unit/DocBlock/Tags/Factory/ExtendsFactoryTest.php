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
use phpDocumentor\Reflection\DocBlock\Tags\Extends_;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\PseudoTypes\Generic;
use phpDocumentor\Reflection\Types\Context;
use phpDocumentor\Reflection\Types\Object_;

final class ExtendsFactoryTest extends TagFactoryTestCase
{
    /**
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\Factory\ExtendsFactory::__construct
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\Factory\ExtendsFactory::create
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\Factory\ExtendsFactory::supports
     */
    public function testExtendsIsCreated(): void
    {
        $ast = $this->parseTag('@extends SomeClass<OtherType>');
        $factory = new ExtendsFactory($this->giveTypeResolver(), $this->givenDescriptionFactory());
        $context = new Context('global');

        self::assertTrue($factory->supports($ast, $context));
        self::assertEquals(
            new Extends_(
                new Generic(new Fqsen('\\SomeClass'), [new Object_(new Fqsen('\\OtherType'))]),
                new Description('')
            ),
            $factory->create($ast, $context)
        );
    }
}
