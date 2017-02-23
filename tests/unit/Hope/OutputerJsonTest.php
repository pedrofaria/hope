<?php

use AspectMock\Test as test;
use Hope\Application;
use Hope\Http\Response;

class OutputerJsonTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
        test::clean(); // remove all registered test doubles
    }

    // tests
    public function testItShouldGenerateACorrectJsonFromArray()
    {
        test::double('Hope\Router\Dispatcher', ['dispatch' => ['foo' => 'bar']]);
        
        $this->expectOutputString('{"foo":"bar"}');

        $app = new Application;
        $app->bootstrap();
        $app->run();
    }

    public function testItShouldGenerateACorrectJsonFromResponseObject()
    {
        $routers = [
            ['GET', '/test', function (Response $response) {
                return $response->easyResponse(['test' => 'testValue'], 201);
            }]
        ];
        test::double('Hope\Router', ['getConfiguredRoutes' => $routers]);
        test::double(
            'Hope\Http\Request',
            [
                'getMethod' => 'GET',
                'getUriForRouter' => '/test'
            ]
        );

        $this->expectOutputString('{"test":"testValue"}');

        $app = new Application;
        $app->bootstrap();
        $app->run();

        $response = $app->get('Hope\Http\Response');
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testItShouldGenerateACorrectJsonFromResponseObjectWithStringContent()
    {
        $routers = [
            ['GET', '/test', function (Response $response) {
                return $response->easyResponse('foobar');
            }]
        ];
        test::double('Hope\Router', ['getConfiguredRoutes' => $routers]);
        test::double(
            'Hope\Http\Request',
            [
                'getMethod' => 'GET',
                'getUriForRouter' => '/test'
            ]
        );

        $this->expectOutputString('foobar');

        $app = new Application;
        $app->bootstrap();
        $app->run();

        $response = $app->get('Hope\Http\Response');
    }

    public function testItShouldGenerateACorrectJsonFromHttpException()
    {
        $routers = [
            ['GET', '/test', function (Response $response) {
                return '';
            }]
        ];
        test::double('Hope\Router', ['getConfiguredRoutes' => $routers]);
        test::double(
            'Hope\Http\Request',
            [
                'getMethod' => 'GET',
                'getUriForRouter' => '/test2'
            ]
        );

        $this->expectOutputString('{"code":404,"message":"Page not Found"}');

        $app = new Application;
        $app->bootstrap();
        $app->run();
    }

    public function testItShouldGenerateACorrectJsonException()
    {
        $routers = [
            ['GET', '/test', function (Response $response) {
                throw new Exception('Error', '1001');
                return '';
            }]
        ];
        test::double('Hope\Router', ['getConfiguredRoutes' => $routers]);
        test::double(
            'Hope\Http\Request',
            [
                'getMethod' => 'GET',
                'getUriForRouter' => '/test'
            ]
        );

        $this->expectOutputString('{"code":1001,"message":"Error"}');

        $app = new Application;
        $app->bootstrap();
        $app->run();
    }
}