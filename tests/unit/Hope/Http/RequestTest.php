<?php

use AspectMock\Test as test;
use Hope\Http\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

class RequestTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _after()
    {
        test::clean(); // remove all registered test doubles
    }

    // tests
    public function testItGetUriForRouterShouldBeValidWithQueryString()
    {
        $request = new Request;
        $request->server->set('REQUEST_URI', '/my/foo/bar/path?aaa=bbb&ccc=ddd');
        $output = $request->getUriForRouter();
        
        $this->assertEquals('/my/foo/bar/path', $output);
    }

    public function testItGetUriForRouterShouldBeValidWithoutQueryString()
    {
        $request = new Request;
        $request->server->set('REQUEST_URI', '/my/foo/bar/path');
        $output = $request->getUriForRouter();
        
        $this->assertEquals('/my/foo/bar/path', $output);
    }
}