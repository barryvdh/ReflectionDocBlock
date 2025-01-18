<?php
/**
 * phpDocumentor Param tag test.
 * 
 * PHP version 5.3
 *
 * @author    Mike van Riel <mike.vanriel@naenius.com>
 * @copyright 2010-2011 Mike van Riel / Naenius. (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace Barryvdh\Reflection\DocBlock\Tag;

use PHPUnit\Framework\TestCase;

/**
 * Test class for \Barryvdh\Reflection\DocBlock\ParamTag
 *
 * @author    Mike van Riel <mike.vanriel@naenius.com>
 * @copyright 2010-2011 Mike van Riel / Naenius. (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */
class ParamTagTest extends TestCase
{
    /**
     * Test that the \Barryvdh\Reflection\DocBlock\Tag\ParamTag can
     * understand the @param DocBlock.
     *
     * @param string $type
     * @param string $content
     * @param string $extractedType
     * @param string $extractedTypes
     * @param string $extractedVarName
     * @param string $extractedDescription
     *
     * @covers \Barryvdh\Reflection\DocBlock\Tag\ParamTag
     * @dataProvider provideDataForConstructor
     *
     * @return void
     */
    public function testConstructorParsesInputsIntoCorrectFields(
        $type,
        $content,
        $extractedType,
        $extractedTypes,
        $extractedVarName,
        $extractedDescription
    ) {
        $tag = new ParamTag($type, $content);

        $this->assertEquals($type, $tag->getName());
        $this->assertEquals($extractedType, $tag->getType());
        $this->assertEquals($extractedTypes, $tag->getTypes());
        $this->assertEquals($extractedVarName, $tag->getVariableName());
        $this->assertEquals($extractedDescription, $tag->getDescription());
    }

    /**
     * Data provider for testConstructorParsesInputsIntoCorrectFields()
     *
     * @return array
     */
    public function provideDataForConstructor()
    {
        return array(
            array('param', 'int', 'int', array('int'), '', ''),
            array('param', '$bob', '', array(), '$bob', ''),
            array(
                'param',
                'int Number of bobs',
                'int',
                array('int'),
                '',
                'Number of bobs'
            ),
            array(
                'param',
                'int $bob',
                'int',
                array('int'),
                '$bob',
                ''
            ),
            array(
                'param',
                'int $bob Number of bobs',
                'int',
                array('int'),
                '$bob',
                'Number of bobs'
            ),
            array(
                'param',
                "int Description \n on multiple lines",
                'int',
                array('int'),
                '',
                "Description \n on multiple lines"
            ),
            array(
                'param',
                "int \n\$bob Variable name on a new line",
                'int',
                array('int'),
                '$bob',
                "Variable name on a new line"
            ),
            array(
                'param',
                "\nint \$bob Type on a new line",
                'int',
                array('int'),
                '$bob',
                "Type on a new line"
            ),

            // generic array
            array(
                'param',
                'array<int, string> $names',
                'array<int, string>',
                array('array<int, string>'),
                '$names',
                ''
            ),

            // nested generics
            array(
                'param',
                'array<int, array<string, mixed>> $arrays',
                'array<int, array<string, mixed>>',
                array('array<int, array<string, mixed>>'),
                '$arrays',
                ''
            ),

            // closure
            array(
                'param',
                '(\Closure(int, string): bool) $callback',
                '(\Closure(int, string): bool)',
                array('(\Closure(int, string): bool)'),
                '$callback',
                ''
            ),

            // generic array in closure
            array(
                'param',
                '(\Closure(array<int, string>): bool) $callback',
                '(\Closure(array<int, string>): bool)',
                array('(\Closure(array<int, string>): bool)'),
                '$callback',
                ''
            ),

            // union types in closure
            array(
                'param',
                '(\Closure(int|string): bool)|bool $callback',
                '(\Closure(int|string): bool)|bool',
                array('(\Closure(int|string): bool)', 'bool'),
                '$callback',
                ''
            ),

            // example from Laravel Framework - Eloquent Builder)
            array(
                'param',
                'array<array-key, array|(\Closure(\Illuminate\Database\Eloquent\Relations\Relation<*,*,*>): mixed)|string>|string  $relations',
                'array<array-key, array|(\Closure(\Illuminate\Database\Eloquent\Relations\Relation<*,*,*>): mixed)|string>|string',
                array('array<array-key, array|(\Closure(\Illuminate\Database\Eloquent\Relations\Relation<*,*,*>): mixed)|string>', 'string'),
                '$relations',
                ''
            ),
            array(
                'param',
                '(\Closure(\Illuminate\Database\Eloquent\Relations\Relation<*,*,*>): mixed)|string|null  $callback',
                '(\Closure(\Illuminate\Database\Eloquent\Relations\Relation<*,*,*>): mixed)|string|null',
                array('(\Closure(\Illuminate\Database\Eloquent\Relations\Relation<*,*,*>): mixed)', 'string', 'null'),
                '$callback',
                ''
            ),

            // array shapes
            array(
                'param',
                'array{foo: string, bar: int} $array',
                'array{foo: string, bar: int}',
                array('array{foo: string, bar: int}'),
                '$array',
                ''
            ),
            array(
                'param',
                'MyArray[\'key\'] $value',
                'MyArray[\'key\']',
                array('MyArray[\'key\']'),
                '$value',
                ''
            )
        );
    }
}
