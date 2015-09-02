<?php
namespace FzySlimCoreTest\Util;

use FzySlimCore\Util\Mode;

class ModeTest extends \PHPUnit_Framework_TestCase {
    /**
     * @test
     */
    public function constructorInvokesAppMode()
    {
        $configs = [
            'one' => [1,],
            'two' => [6,4,3],
            'three' => [9,3,1,6],
        ];
        $app = $this->getMockBuilder('\Slim\Slim')->disableOriginalConstructor()->getMock();
        $app->expects($this->exactly(count($configs)))->method('configureMode');
        $app->expects($this->never())->method('config');
        Mode::configureModes($app, $configs);
    }

}