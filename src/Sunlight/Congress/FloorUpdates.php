<?php
/**
 * Districts.php - Congress API Wrapper
 *
 * The API Wrapper provides an interface to construct Sunlight Foundation Congress API queries.
 *
 * @link           http://sunlightlabs.github.io/congress/districts.html
 *
 * @author         Samantha Quinones <samantha@tembies.com>
 * @package        Sunlight\Congress
 * @copyright      2013 Samantha Quinones
 * @license        MIT (For the full copyright and license information, please view the LICENSE
 *                 file that was distributed with this source code.)
 */
namespace Sunlight\Congress;


use Sunlight\Congress\Api\ApiWrapper;

class Districts extends ApiWrapper
{
    protected $url = "http://congress.api.sunlightfoundation.com/districts/locate";

    /**
     * Query the /locate subordinate endpoint
     *
     * @link http://sunlightlabs.github.io/congress/districts.html
     *
     * @param $location
     *
     * @return Api\ApiResponse
     */
    public function locate($location)
    {
        // Clone the wrapper
        $districts = clone $this;

        // Create an empty filter
        $filter = new Filter();

        // If the location is an array, assume a lat/long pair, otherwise a zip code
        if (is_array($location)) {
            list($filter["latitude"], $filter["longitude"]) = $location;
        } else {
            $filter["zip"] = $location;
        }

        // Set the filter
        $districts->filter($filter);

        // Run the find method
        return $districts->filter($filter)->find();
    }
}