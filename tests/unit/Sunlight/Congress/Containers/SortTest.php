<?php
/**
 * Created by JetBrains PhpStorm.
 * User: squinones
 * Date: 9/21/13
 * Time: 8:11 PM
 * To change this template use File | Settings | File Templates.
 */

class SortTest extends PHPUnit_Framework_TestCase
{
    /**
     * @param $sortParams
     * @dataProvider sortParamsProvider
     */
    public function testSortCastsToStringProperly($sortParams)
    {
        $expected = $sortParams[0];

        if ($expected instanceof InvalidArgumentException) {
            $this->setExpectedException("InvalidArgumentException");
        }
        $sort = new \Sunlight\Congress\Containers\Sort($sortParams[1]);
        $this->assertEquals($expected, (string) $sort);
    }

    public function sortParamsProvider()
    {
        return array(
            array(array(
                "foo__asc,bar__desc",
                array("foo" => "asc", "bar" => "desc")
            )),
            array(array(
                new InvalidArgumentException("Sort parameter directions must be 'asc' or 'desc'"),
                array("foo" => "invalid", "bar" => "desc")
            )),
            array(array(
                new InvalidArgumentException("Sort parameter directions must be 'asc' or 'desc'"),
                array("foo" => "asc", "bar" => "invalid")
            ))
        );
    }
}
