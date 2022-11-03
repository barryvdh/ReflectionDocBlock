<?php
/**
 * phpDocumentor SuppressWarnings Tag Test
 *
 * PHP version 5.3
 *
 * @author    Andrew Smith <espadav8@gmail.com>
 * @copyright 2010-2011 Mike van Riel / Naenius. (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace Barryvdh\Reflection\DocBlock\Tag;

use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;

/**
 * Test class for \Barryvdh\Reflection\DocBlock\Tag\SuppressWarningsTag
 *
 * @author    Andrew Smith <espadav8@gmail.com>
 * @copyright 2010-2011 Mike van Riel / Naenius. (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */
class SuppressWarningsTagTest extends TestCase
{
    /**
     * Test that the \Barryvdh\Reflection\DocBlock\Tag\SuppressWarningsTag can
     * understand the @SuppressWarnings doc block.
     *
     * @param string $type
     * @param string $content
     * @param string $exType
     * @param string $exVariable
     * @param string $exDescription
     *
     * @covers \Barryvdh\Reflection\DocBlock\Tag\SuppressWarningsTag
     * @dataProvider provideDataForConstuctor
     *
     * @return void
     */
    public function testConstructorParesInputsIntoCorrectFields(
        $type,
        $content,
        $description
    ) {
        $tag = new SuppressWarningsTag($type, $content);

        $this->assertEquals($type, $tag->getName());
        $this->assertEquals($description, $tag->getDescription());
    }

    /**
     * Data provider for testConstructorParesInputsIntoCorrectFields
     *
     * @return array
     */
    public function provideDataForConstuctor()
    {
        // $type, $content, $description
        return array(
            array(
                'SuppressWarnings',
                'SuppressWarnings(PHPMD)',
                'SuppressWarnings(PHPMD)',
            ),
            array(
                'SuppressWarnings',
                'SuppressWarnings(PHPMD.TooManyMethods)',
                'SuppressWarnings(PHPMD.TooManyMethods)',
            ),
        );
    }
}
