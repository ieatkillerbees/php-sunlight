<?php
/**
 * Amendments.php - Congress API Wrapper
 *
 * The API Wrapper provides an interface to construct Sunlight Foundation Congress API queries.
 *
 * @link           https://sunlightlabs.github.io/congress/amendments.html
 *
 * @author         Samantha Quinones <samantha@tembies.com>
 * @package        Sunlight\Congress
 * @copyright      2013 Samantha Quiñones
 * @license        MIT (For the full copyright and license information, please view the LICENSE
 *                 file that was distributed with this source code.)
 */

namespace Squinones\Sunlight\Congress;


use Squinones\Sunlight\Congress\Api\ApiWrapper;

/**
 * @link    https://sunlightlabs.github.io/congress/committees.html
 * @author  Samantha Quinones <samantha@tembies.com>
 * @package Sunlight\Congress
 */
class Amendments extends ApiWrapper
{
    /**
     * @var string
     */
    protected $url = "https://sunlightlabs.github.io/congress/amendments.html";
}
