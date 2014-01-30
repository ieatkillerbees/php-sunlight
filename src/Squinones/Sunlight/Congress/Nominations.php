<?php
/**
 * Nominations.php - Congress API Wrapper
 *
 * The API Wrapper provides an interface to construct Sunlight Foundation Congress API queries.
 *
 * @link           http://sunlightlabs.github.io/congress/nominations.html
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
 * @link    http://sunlightlabs.github.io/congress/nominations.html
 * @author  Samantha Quinones <samantha@tembies.com>
 * @package Sunlight\Congress
 */
class Nominations extends ApiWrapper
{
    /**
     * @var string
     */
    protected $url = "http://congress.api.sunlightfoundation.com/nominations";
}
