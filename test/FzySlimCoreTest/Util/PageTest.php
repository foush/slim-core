<?php
namespace FzySlimCoreTest\Util;

use FzySlimCore\Util\Page;

class PageTest extends \PHPUnit_Framework_TestCase {
    /**
     * @test
     */
    public function constructorWorks()
    {
        $page = new Page();
        $this->assertEquals(0, $page->getOffset());
        $this->assertEquals($page->getDefaultLimit(), $page->getLimit());
        $offset = 234234;
        $page = new Page($offset);
        $this->assertEquals($offset, $page->getOffset());
        $this->assertEquals($page->getDefaultLimit(), $page->getLimit());
        $limit = 41;
        $page = new Page($offset, $limit);
        $this->assertEquals($offset, $page->getOffset());
        $this->assertEquals($limit, $page->getLimit());
    }

    /**
     * @test
     */
    public function negativeOffsetBecomesZero()
    {
        $page = new Page(-1);
        $this->assertEquals(0, $page->getOffset());
        $this->assertInternalType('int', $page->getOffset());
    }

    /**
     * @test
     */
    public function nonIntegerOffsetBecomesZero()
    {
        $page = new Page('somethin');
        $this->assertEquals(0, $page->getOffset());
        $this->assertInternalType('int', $page->getOffset());
    }

    /**
     * @test
     */
    public function limitOverMaxSetToDefault()
    {
        $limit = 123;
        $page = new Page(0, $limit);
        $this->assertTrue($limit > 2);
        $page->setLimitBounds($limit - 1, $limit - 2);
        $this->assertEquals($page->getDefaultLimit(), $page->getLimit());
    }

    /**
     * @test
     * @expectedException \FzyUtils\Exception\Configuration\Invalid
     */
    public function invalidLimitBoundsCausesException()
    {
        $max = 34;
        $page = new Page();
        $page->setLimitBounds($max, $max + 1);
    }

    /**
     * @test
     */
    public function maxLimitSetter()
    {
        $page = new Page();
        $maxLimit = 14;
        $this->assertNotEquals($page->getMaxLimit(), $maxLimit);
        $page->setLimitBounds($maxLimit);
        $this->assertEquals($page->getMaxLimit(), $maxLimit);
    }

    /**
     * @test
     */
    public function setMaxLimitWithoutDefaultDoesNotChangeDefault()
    {
        $page = new Page();
        $maxLimit = 14;
        $default = $page->getDefaultLimit();
        $this->assertNotEquals($page->getMaxLimit(), $maxLimit);
        $page->setLimitBounds($maxLimit);
        $this->assertEquals($page->getMaxLimit(), $maxLimit);
        $this->assertEquals($page->getDefaultLimit(), $default);
    }

    /**
     * @test
     */
    public function staticSetter()
    {
        $mockRequest = $this->getMockBuilder('\Slim\Http\Request')->disableOriginalConstructor()->getMock();
        $offset = 3;
        $limit = 16;
        $mockRequest->expects($this->exactly(2))->method('get')->willReturnMap([
            ['offset', null, $offset],
            ['limit', 10, $limit],
        ]);
        $page = Page::createFromRequest($mockRequest);
        $this->assertEquals($offset, $page->getOffset());
        $this->assertEquals($limit, $page->getLimit());
    }
}