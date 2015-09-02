<?php
namespace FzySlimCore\Util;

class Result extends AbstractOutput {

    /**
     * @var Page
     */
    protected $page;

    /**
     * @var array
     */
    protected $results;

    /**
     * @var int
     */
    protected $total;

    public function __construct(array $results, Page $page, $total = null)
    {
        $this->results = $results;
        $this->page = $page;
        $this->total = $total === null ? count($results) : intval($total);
    }

    /**
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param Page $page
     */
    public function setPage(Page $page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param array $results
     */
    public function setResults(array $results)
    {
        $this->results = $results;
        return $this;
    }

    /**
     * Returns the result data at offset, defaults to the first
     * @param int $offset
     * @return mixed
     */
    public function getResult($offset = 0)
    {
        return isset($this->results[$offset]) ? $this->results[$offset] : null;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }



    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    function jsonSerialize()
    {
        return [
            'meta' => [
                'offset' => $this->page->getOffset(),
                'limit' => $this->page->getLimit(),
                'total' => $this->total,
            ],
            'data' => $this->results,
        ];
    }


}