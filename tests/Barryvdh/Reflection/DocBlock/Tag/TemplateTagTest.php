<?php

/**
 * phpDocumentor Template Tag Test
 *
 * PHP version 5.3
 *
 * @author    Daniel O'Connor <daniel.oconnor@gmail.com>
 * @copyright 2010-2011 Mike van Riel / Naenius. (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace Barryvdh\Reflection\DocBlock\Tag;

use PHPUnit\Framework\TestCase;

/**
 * Test class for \Barryvdh\Reflection\DocBlock\Tag\TemplateTag
 *
 * @author    Daniel O'Connor <daniel.oconnor@gmail.com>
 * @copyright 2010-2011 Mike van Riel / Naenius. (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */
class TemplateTagTest extends TestCase
{
    /**
     * Test that the phpDocumentor_Reflection_DocBlock_Tag_See can create a link
     * for the @see doc block.
     *
     * @param string $type
     * @param string $content
     * @param string $exContent
     * @param string $exReference
     *
     * @covers \Barryvdh\Reflection\DocBlock\Tag\SeeTag
     * @dataProvider provideDataForConstuctor
     *
     * @return void
     */
    public function testConstructorParesInputsIntoCorrectFields(
        $type,
        $content,
        $exContent,
        $exDescription,
        $exTemplateName,
        $exBound
    ) {
        $tag = new TemplateTag($type, $content);

        $this->assertEquals($type, $tag->getName());
        $this->assertEquals($exContent, $tag->getContent());
        $this->assertEquals($exDescription, $tag->getDescription());
        $this->assertEquals($exTemplateName, $tag->getTemplateName());
        $this->assertEquals($exBound, $tag->getBound());
    }

    /**
     * Data provider for testConstructorParesInputsIntoCorrectFields
     *
     * @return array
     */
    public function provideDataForConstuctor()
    {
        // $type, $content, $exContent, $exDescription, $exTemplateName, $exBound
        return array(
            array(
                'template',
                'TValue',
                'TValue',
                '',
                'TValue',
                null,
            ),
            array(
                'template',
                'TValue of string',
                'TValue of string',
                '',
                'TValue',
                'string',
            ),
        );
    }
}
