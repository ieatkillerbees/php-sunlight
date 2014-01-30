<?php
/**
 * FloorUpdates.php - Congress API Wrapper
 *
 * The API Wrapper provides an interface to construct Sunlight Foundation Congress API queries.
 *
 * @link           http://sunlightlabs.github.io/congress/floor_updates.html
 *
 * @author         Samantha Quinones <samantha@tembies.com>
 * @package        Sunlight\Congress
 * @copyright      2013 Samantha Quiñones
 * @license        MIT (For the full copyright and license information, please view the LICENSE
 *                 file that was distributed with this source code.)
 */
namespace Squinones\Sunlight\Congress;


use Squinones\Sunlight\Congress\Api\ApiWrapper;

class FloorUpdates extends ApiWrapper
{
    protected $url = "http://congress.api.sunlightfoundation.com/floor_updates";
}
