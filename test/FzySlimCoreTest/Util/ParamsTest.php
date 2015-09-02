<?php
namespace FzySlimCoreTest\Util;

use FzySlimCore\Util\Params;

class ParamsTest extends \PHPUnit_Framework_TestCase {
    /**
     * @test
     */
    public function constructorSetsData()
    {
        $params = new Params();
        $this->assertInternalType('array', $params->get());
        $data = [1,34,5,6];
        $params = new Params($data);
        $this->assertEquals($data, $params->get());
    }

    /**
     * @test
     */
    public function hasFnReturnsCorrectly()
    {
        $key = 'somekey';
        $params = new Params([$key => 'value']);
        $this->assertTrue($params->has($key));
        $this->assertFalse($params->has($key.'1'));
    }

    /**
     * @test
     */
    public function hasFnReturnsCorrectlyForNull()
    {
        $key = 'somekey';
        $params = new Params([$key => null]);
        $this->assertTrue($params->has($key));
    }

    /**
     * @test
     */
    public function getWithNoParamsReturnsAll()
    {
        $data = ['some' => 'value'];
        $params = new Params($data);
        $this->assertEquals($data, $params->get());
    }

    /**
     * @test
     */
    public function getWithOneParam()
    {
        $key = 'somekey';
        $val = 'someval';
        $params = new Params([$key => $val]);
        $this->assertEquals($val, $params->get($key));
        $this->assertNull($params->get($key.'a'));
    }

    /**
     * @test
     */
    public function getWithTwoParams()
    {
        $key = 'somekey';
        $val = 'someval';
        $default = 'another val';
        $params = new Params(['somekey' => 'someval',]);
        $this->assertEquals($val, $params->get($key, $default));
        $this->assertEquals($default, $params->get($key.'a', $default));
    }

    /**
     * @test
     */
    public function setDataStoresOrOverwrites()
    {
        $params = new Params();
        $key = 'somekey';
        $value = 'someval';
        $this->assertNotEquals($value, $params->get($key));
        $params->set($key, $value);
        $this->assertEquals($value, $params->get($key));
    }

    /**
     * @test
     */
    public function setAllOverwritesAllData()
    {
        $oldKey = 'oldkey';
        $params = new Params([$oldKey => 1]);
        $newKey = $oldKey.'a';
        $params->setAll([$newKey => 'val']);
        $this->assertTrue($params->has($newKey));
        $this->assertFalse($params->has($oldKey));
    }

    /**
     * @test
     */
    public function canIterate()
    {
        $data = ['one' => 'val', '3' => 'what', 2 => 'second', 0 => -9];
        $expected = [];
        foreach ($data as $key => $val) {
            $expected[] = [$key, $val];
        }
        $actual = [];
        $params = new Params($data);
        foreach ($params as $key => $val) {
            $actual[] = [$key, $val];
        }
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function getSetAndIsset()
    {
        $key = 'someKey';
        $val = 'someVal';
        $params = new Params([$key => $val]);
        $this->assertEquals($val, $params->$key);
        $this->assertTrue(isset($params->$key));
        $newKey = $key.'a';
        $newVal = $val.'a';
        $this->assertFalse(isset($params->$newKey));
        $params->$newKey = $newVal;
        $this->assertTrue(isset($params->$newKey));
        unset($params->$key);
        $this->assertFalse(isset($params->$key));
    }

    /**
     * @test
     */
    public function countable()
    {
        $params = new Params();
        $this->assertEquals(0, count($params));
        $data = ['0' => 1, '5' => 3, '09' => 2];
        $params->setAll($data);
        $this->assertEquals(count($data), count($params));
    }
}