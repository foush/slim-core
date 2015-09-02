<?php
namespace FzySlimCoreTest\Util;

use FzySlimCore\Util\Page;
use FzySlimCore\Util\Result;

class ResultTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function testConstructorNoTotal()
    {
        $results = ['some', 'result', 'array',];
        $page = new Page();
        $result = new Result($results, $page);
        $this->assertEquals($result->getPage(), $page);
        $this->assertEquals($result->getResults(), $results);
        $this->assertEquals($result->getTotal(), count($results));
    }

    /**
     * @test
     */
    public function testConstructorWithTotal()
    {
        $results = ['some', 'result', 'array',];
        $page = new Page();
        $total = 123132;
        $result = new Result($results, $page, $total);
        $this->assertEquals($result->getPage(), $page);
        $this->assertEquals($result->getResults(), $results);
        $this->assertEquals($result->getTotal(), $total);
    }

    /**
     * @test
     */
    public function settersWork()
    {
        $result = new Result([], new Page());
        $page = new Page(1,2);
        $this->assertNotEquals($result->getPage(), $page);
        $result->setPage($page);
        $this->assertEquals($result->getPage(), $page);
        $results = ['one', 'two', 'three'];
        $this->assertNotEquals($result->getResults(), $results);
        $result->setResults($results);
        $this->assertEquals($result->getResults(), $results);
        $total = 1092;
        $this->assertNoTequals($result->getTotal(), $total);
        $result->setTotal($total);
        $this->assertEquals($result->getTotal(), $total);
    }

    /**
     * @test
     */
    public function resultIsJsonSerializable()
    {
        $data = [
            'one',2,'3'
        ];
        $page = new Page(3,4);
        $total = 322;
        $result = new Result($data, $page, $total);
        $stringified = (string)$result;
        $this->assertEquals(json_encode([
            'meta' => [
                'offset' => $page->getOffset(),
                'limit' => $page->getLimit(),
                'total' => $total,
            ],
            'data' => $data,
        ]), $stringified);
    }

}