<?php
/**
 * ApiResponse.php - Congress API Response Wrapper
 *
 * API Response represents a response from the Sunlight Foundation Congress API as an iterable object. The count of
 * results is available as ApiResponse::count().
 *
 * @author         Samantha Quinones <samantha@tembies.com>
 * @package        Sunlight\Congress
 * @copyright      2013 Samantha Quinones
 * @license        MIT (For the full copyright and license information, please view the LICENSE
 *                 file that was distributed with this source code.)
 */
namespace Sunlight\Congress\Api;

use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use Guzzle\Service\Client;

/**
 * Iterable result set.
 *
 * @author  Samantha Quinones <samantha@tembies.com>
 * @package Sunlight\Congress\Api
 *
 */
class ApiResponse implements \Iterator
{
    /**
     * A Guzzle request, usually created by an ApiWrapper
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * The complete response array as decoded from the JSON response
     *
     * @var array
     */
    protected $response;

    /**
     * Total count of results
     *
     * @var int
     */
    private $_count;

    /**
     * The current position in the resultset. If null, the resultset is empty or the pointer has moved past the last
     * result.
     *
     * @var int|null
     */
    private $_current;

    /**
     * The most recent "page" of data that was returned.
     *
     * @var int
     */
    private $_page;

    /**
     * The total number of pages that exist for the query.
     *
     * @var int
     */
    private $_pages;

    /**
     * The number of items that constitute a "page" of data
     *
     * @var int
     */
    private $_perPage;

    /**
     * @param \Guzzle\Http\Message\RequestInterface $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Guzzle\Http\Message\RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param array $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Takes a Guzzle RequestInterface and executes it, initializing the Response object based on the response.
     *
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        // Store the request
        $this->request = $request;

        // Execute the query
        $this->response = $this->request->send()->json();

        // Store the results
        $this->results = $this->response["results"];
        unset($this->response["results"]);

        // Record the information needed by the iterator
        $this->_count   = $this->response["count"];
        $this->_current = (count($this->results) > 0) ? 0 : null;
        $this->_page    = isset($this->response["page"]) ? $this->response["page"]["page"] : 0;
        $this->_pages   = isset($this->response["page"]["count"]) ? $this->response["page"]["count"] : 0;
        $this->_perPage = isset($this->response["page"]["per_page"]) ? $this->response["page"]["per_page"] : 0;
    }

    /**
     * Returns the total number of results returned
     *
     * @return int
     */
    public function count()
    {
        return $this->_count;
    }

    /**
     * Return the current element. Returns null if the current element is invalid.
     *
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        if (!is_null($this->_current) && isset($this->results[$this->_current])) {
            return $this->results[$this->_current];
        } else {
            return null;
        }
    }

    /**
     * Move forward to next element
     *
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        // If the current element is the last element, return null
        if ($this->_current === ($this->count() - 1)) {
            $this->_current = null;

            return;
        }

        // If the next element is greater than the result count, and this is the last page, return null
        if (($this->_current + 1) > count($this->results) && (($this->_page + 1) > $this->_pages)) {
            $this->_current = null;

            return;
        }

        // Advance to the next page
        $this->request->getQuery()->set("page", ++$this->_page);

        // Retrieve the next page
        $response = $this->request->send()->json();

        // Append the results to the results array
        $this->results = array_merge($this->results, $response["results"]);

        // Advance to the next element
        $this->_current++;

        return;
    }

    /**
     * Return the key of the current element
     *
     * @link http://php.net/manual/en/iterator.key.php
     *
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->_current;
    }

    /**
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php
     *
     * @return boolean The return value will be casted to boolean and then evaluated.
     *       Returns true on success or false on failure.
     */
    public function valid()
    {
        if (is_null($this->_current)) {
            return false;
        }

        return true;
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     *
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->_current = 0;
    }
}