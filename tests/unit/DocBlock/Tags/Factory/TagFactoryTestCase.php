<?php
/*
 * This file is part of phpDocumentor.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 *  @link      http://phpdoc.org
 *
 */

declare(strict_types=1);

namespace phpDocumentor\Reflection\DocBlock\Tags\Factory;

use Mockery as m;
use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use phpDocumentor\Reflection\FqsenResolver;
use phpDocumentor\Reflection\TypeResolver;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\PhpDocParser\ParserConfig;
use PHPUnit\Framework\TestCase;

use function class_exists;
use function property_exists;

abstract class TagFactoryTestCase extends TestCase
{
    public function parseTag(string $tag): PhpDocTagNode
    {
        if (class_exists(ParserConfig::class)) {
            $config = new ParserConfig([]);
            $lexer = new Lexer($config);
            $constParser = new ConstExprParser($config);
            $phpDocParser = new PhpDocParser($config, new TypeParser($config, $constParser), $constParser);
        } else {
            $lexer = new Lexer();
            $constParser = new ConstExprParser();
            $phpDocParser = new PhpDocParser(new TypeParser($constParser), $constParser);
        }

        $tagNode = $phpDocParser->parseTag(new TokenIterator($lexer->tokenize($tag)));
        if (property_exists($tagNode->value, 'description') === true) {
            $tagNode->value->setAttribute('description', $tagNode->value->description);
        }

        return $tagNode;
    }

    public function giveTypeResolver(): TypeResolver
    {
        return new TypeResolver(new FqsenResolver());
    }

    public function givenDescriptionFactory(): DescriptionFactory
    {
        $factory =  m::mock(DescriptionFactory::class);
        $factory->shouldReceive('create')->andReturnUsing(static fn ($args) => new Description($args));

        return $factory;
    }

    /**
     * Call Mockery::close after each test.
     *
     * @after
     */
    public function closeMockery(): void
    {
        m::close();
    }
}
