<?php
/**
 * ApiWrapper.php - Congress API Wrapper
 *
 * The API Wrapper provides an interface to construct Sunlight Foundation Congress API queries.
 *
 * @author         Samantha Quinones <samantha@tembies.com>
 * @package        Sunlight\Congress
 * @copyright      2013 Samantha Quinones
 * @license        MIT (For the full copyright and license information, please view the LICENSE
 *                 file that was distributed with this source code.)
 */

namespace Sunlight\Congress\Api;

use Guzzle\Http\Client;
use Sunlight\Congress\Api\ApiResponse;
use Sunlight\Congress\Containers\FieldList;
use Sunlight\Congress\Containers\Filter;
use Sunlight\Congress\Containers\Sort;

/**
 * Abstraction for the Sunlight Foundation's Congress REST API
 *
 * @author  Samantha Quinones <samantha@tembies.com>
 * @package Sunlight\Congress\Api
 */
class ApiWrapper
{
    /**
     * Sunlight Foundation API key.
     *
     * @link http://www.sunlightfoundation.com/
     * @var string
     */
    protected $apiKey;

    /**
     * The url of the REST endpoint we will be wrapping.
     *
     * @var string
     */
    protected $url;

    /**
     * A Guzzle client
     *
     * @var Client
     */
    protected $client;

    /**
     * A Sunlight\Congress\FieldList object that will contain a list of fields to include in the results
     *
     * @var FieldList
     */
    private $_fields;

    /**
     * A Sunlight\Congress\Sort object that will contain a list of fields and sort directions.
     *
     * @var Sort
     */
    private $_sort;

    /**
     * A Sunlight\Congress\Filter object containing key=>value pairs of fields and values/expressions on which to
     * filter. For more information, see the Sunlight Foundation API documentation here: {@link http://sunlightlabs.github.io/congress/index.html}
     *
     * @var Filter
     */
    private $_filter;

    /**
     * Takes an optional API key argument.
     *
     * @param null|string $apiKey
     */
    public function __construct($apiKey = null)
    {
        if (!is_null($apiKey)) {
            $this->setApiKey($apiKey);
        }

        return $this;
    }

    /**
     * Returns an ApiResponse object. The optional 'query' parameter can contain plain text that will be used as a
     * search query as documented here: {@link http://sunlightlabs.github.io/congress/index.html#parameters/basic-search}
     *
     * @param null|string $query
     *
     * @return ApiResponse
     */
    public function find($query = null)
    {
        $request = $this->getClient()->get();
        if ($this->getFilter()) {
            $request->getQuery()->replace((array)$this->getFilter());
        }
        if ($this->getSort()) {
            $request->getQuery()->add("order", (string)$this->getSort());
        }
        if ($this->getFields()) {
            $request->getQuery()->add("fields", (string)$this->getFields());
        }
        if (!is_null($query)) {
            $request->getQuery()->add("query", $query);
        }
        $request->getQuery()->add("apikey", $this->getApiKey());

        return new ApiResponse($request);
    }

    /**
     * @param FieldList|array $fields
     *
     * @throws \BadMethodCallException
     */
    public function setFields($fields)
    {
        if ($fields instanceof FieldList) {
            $this->_fields = $fields;
        } elseif (is_array($fields)) {
            $this->_fields = new FieldList($fields);
        } else {
            throw new \BadMethodCallException(sprintf("%s::fields - first argument must be array or instance of %s", get_class($this), __NAMESPACE__ . "\\FieldSet"));
        }
    }

    /**
     * @return FieldList
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * @param array|Filter $filter    protected s
     *
     * @throws \BadMethodCallException
     */
    public function setFilter($filter)
    {
        if ($filter instanceof Filter) {
            $this->_filter = $filter;
        } elseif (is_array($filter)) {
            $this->_filter = new Filter($filter);
        } else {
            throw new \BadMethodCallException(sprintf("%s::filter - first argument must be array or instance of %s", get_class($this), __NAMESPACE__ . "\\Sort"));
        }
    }

    /**
     * @return mixed
     */
    public function getFilter()
    {
        return $this->_filter;
    }

    /**
     * @param array|Sort $sort
     *
     * @throws \BadMethodCallException
     */
    public function setSort($sort)
    {
        if ($sort instanceof Sort) {
            $this->_sort = $sort;
        } elseif (is_array($sort)) {
            $this->_sort = new Sort($sort);
        } else {
            throw new \BadMethodCallException(sprintf("%s::sort - first argument must be array or instance of %s", get_class($this), __NAMESPACE__ . "\\Sort"));
        }
    }

    /**
     * @return Sort
     */
    public function getSort()
    {
        return $this->_sort;
    }

    /**
     * @param  array|FieldList $fields
     *
     * @return $this
     */
    public function fields($fields)
    {
        $this->setFields($fields);

        return $this;
    }

    /**
     * @param array|Sort $sort
     *
     * @return $this
     */
    public function sort($sort)
    {
        $this->setSort($sort);

        return $this;
    }

    /**
     * @param $filter
     *
     * @return $this
     */
    public function filter($filter)
    {
        $this->setFilter($filter);

        return $this;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        if (!isset($this->client)) {
            $this->client = new Client($this->getUrl());
        }

        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @throws \RuntimeException
     * @return string
     */
    public function getUrl()
    {
        if (!isset($this->url)) {
            throw new \RuntimeException("No URL configured for this wrapper!");
        }

        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @throws \RuntimeException
     * @return string
     */
    public function getApiKey()
    {
        if (!isset($this->apiKey)) {
            if (getenv('SUNLIGHT_API_KEY')) {
                $this->apiKey = getenv('SUNLIGHT_API_KEY');
            } else {
                throw new \RuntimeException("You must set a valid API key as the first parameter to the constructor, " .
                                            "by calling " . get_class($this) . "::setApiKey, " .
                                            "or in the environment variable 'SUNLIGHT_API_KEY'");
            }
        }

        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }
}