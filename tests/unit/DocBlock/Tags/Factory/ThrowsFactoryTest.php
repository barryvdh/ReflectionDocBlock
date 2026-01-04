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
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use phpDocumentor\Reflection\Types\Context;
use phpDocumentor\Reflection\Types\String_;

final class ThrowsFactoryTest extends TagFactoryTestCase
{
    /**
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\Factory\ThrowsFactory::__construct
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\Factory\ThrowsFactory::create
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\Factory\ThrowsFactory::supports
     */
    public function testThrowsIsCreated(): void
    {
        $ast = $this->parseTag('@throws string');
        $factory = new ThrowsFactory($this->giveTypeResolver(), $this->givenDescriptionFactory());
        $context = new Context('global');

        self::assertTrue($factory->supports($ast, $context));
        self::assertEquals(
            new Throws(
                new String_(),
                new Description('')
            ),
            $factory->create($ast, $context)
        );
    }
}
