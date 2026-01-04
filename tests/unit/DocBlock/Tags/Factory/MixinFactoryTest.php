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
use phpDocumentor\Reflection\DocBlock\Tags\Mixin;
use phpDocumentor\Reflection\Types\Context;
use phpDocumentor\Reflection\Types\String_;

final class MixinFactoryTest extends TagFactoryTestCase
{
    /**
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\Factory\MixinFactory::__construct
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\Factory\MixinFactory::create
     * @covers \phpDocumentor\Reflection\DocBlock\Tags\Factory\MixinFactory::supports
     */
    public function testMixinIsCreated(): void
    {
        $ast = $this->parseTag('@mixin string');
        $factory = new MixinFactory($this->giveTypeResolver(), $this->givenDescriptionFactory());
        $context = new Context('global');

        self::assertTrue($factory->supports($ast, $context));
        self::assertEquals(
            new Mixin(
                new String_(),
                new Description('')
            ),
            $factory->create($ast, $context)
        );
    }
}
