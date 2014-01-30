<?php
/**
 * Created by JetBrains PhpStorm.
 * User: squinones
 * Date: 9/21/13
 * Time: 8:46 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Squinones\Sunlight\Congress\Tests;


use Squinones\Sunlight\Congress\Containers\FieldList;

class FieldListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $fields
     * @dataProvider fieldListProvider
     */
    public function testFieldListCastsToStringProperly($fields)
    {
        $fieldList = new FieldList($fields);
        $this->assertEquals(implode(",", $fields), (string) $fieldList);
    }

    public function fieldListProvider()
    {
        return array(
            array(array("foo", "bar", "baz"))
        );
    }
}
