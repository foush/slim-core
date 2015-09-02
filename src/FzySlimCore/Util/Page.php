<?php
namespace FzySlimCore\Util;

use FzySlimCore\Exception\Configuration\Invalid as InvalidConfigurationException;

class Page {

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var int
     */
    protected $limit;

    /**
     * The max possible value for limit
     * @var int
     */
    protected $maxLimit = 50;

    /**
     * The fallback value if a requested page's limit exceeds the max
     * @var int
     */
    protected $defaultLimit = 10;

    public function __construct($offset = 0, $limit = null)
    {
        $this->setOffset($offset);
        $this->setLimit($limit);
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param $this $offset
     */
    public function setOffset($offset)
    {
        $offset = intval($offset);
        if ($offset < 0) {
            $offset = 0;
        }
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        if ($this->limit > $this->maxLimit) {
            $this->limit = $this->defaultLimit;
        } else if ($this->limit == 0) {
            $this->limit = $this->defaultLimit;
        }
        return $this->limit;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit)
    {
        $this->limit = intval($limit);
        return $this;
    }

    public function setLimitBounds($maxLimit, $defaultLimit = null)
    {
        $maxLimit = intval($maxLimit);
        $defaultLimit = $defaultLimit === null ? $this->getDefaultLimit() : intval($defaultLimit);
        if ($defaultLimit > $maxLimit) {
            throw new InvalidConfigurationException("Invalid bounds: default ($defaultLimit) > max ($maxLimit)");
        }
        $this->maxLimit = $maxLimit;
        $this->defaultLimit = $defaultLimit;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxLimit()
    {
        return $this->maxLimit;
    }

    /**
     * @return int
     */
    public function getDefaultLimit()
    {
        return $this->defaultLimit;
    }

    /**
     * Creates a page object out of a request object
     * @param \Slim\Http\Request $request
     * @return Page
     */
    public static function createFromRequest(\Slim\Http\Request $request)
    {
        return new Page($request->get('offset'), $request->get('limit', 10));
    }
}