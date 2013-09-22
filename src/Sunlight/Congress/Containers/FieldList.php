<?php
/**
 * FieldList.php - List of fields
 *
 * Fields to include in the results of an API query.
 *
 * @link           http://sunlightlabs.github.io/congress/index.html#parameters/partial-responses
 *
 * @author         Samantha Quinones <samantha@tembies.com>
 * @package        Sunlight\Congress
 * @copyright      2013 Samantha Quinones
 * @license        MIT (For the full copyright and license information, please view the LICENSE
 *                 file that was distributed with this source code.)
 */

namespace Sunlight\Congress\Containers;


/**
 * Class FieldList
 *
 * @package Sunlight\Congress\Containers
 */
class FieldList
{
    /**
     * Simple array of fields
     *
     * @var array
     */
    protected $fields;

    /**
     * Takes and stores a one-dimensional array of fields
     *
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * Return array as a comma-delimited list
     *
     * @return string
     */
    function __toString()
    {
        return implode(",", $this->fields);
    }
}