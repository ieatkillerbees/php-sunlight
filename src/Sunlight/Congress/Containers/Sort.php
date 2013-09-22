<?php
/**
 * Sort.php - Sort Object
 *
 * Sort represents the ordering criteria.
 *
 * @link           http://sunlightlabs.github.io/congress/index.html#parameters/sorting
 *
 * @author         Samantha Quinones <samantha@tembies.com>
 * @package        Sunlight\Congress
 * @copyright      2013 Samantha Quinones
 * @license        MIT (For the full copyright and license information, please view the LICENSE
 *                 file that was distributed with this source code.)
 */

namespace Sunlight\Congress\Containers;


/**
 * Sorting Criteria Object
 *
 * @package Sunlight\Congress\Containers
 */
class Sort
{
    /**
     * Takes and stores a hash of fieldname => sort direction
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        if ($this->validParams($params)) {
            $this->params = $params;
        }
    }

    /**
     * Loop the parameters. If any of the directions is invalid, throw out an exception.
     *
     * @param $params
     *
     * @throw  InvalidArgumentException
     *
     * @return bool
     */
    protected function validParams($params)
    {
        array_map(
            function ($direction) {
                if (!in_array(strtolower($direction), array("asc", "desc"))) {
                    throw new \InvalidArgumentException("Sort parameter directions must be 'asc' or 'desc'");
                };
            },
            $params
        );

        return true;
    }

    /**
     * Return a string of 'field__direction,field2__direction'
     *
     * @return string
     */
    public function __toString()
    {
        $sortArray = array();
        foreach ($this->params as $field => $direction) {
            $sortArray[] = sprintf("%s__%s", $field, $direction);
        }

        return implode(",", $sortArray);
    }
}