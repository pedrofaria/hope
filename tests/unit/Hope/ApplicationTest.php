<?php

use AspectMock\Test as test;
use Hope\Application;
use Hope\Contracts\ProviderInterface;
use Hope\DIContainer;

class DiExample
{
    public function exampleMethod($param)
    {
        return "example ". $param;
    }
}

class ApplicationTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function _after()
    {
        test::clean(); // remove all registered test doubles
    }

    // tests
    public function testHopeApplicationShouldBeInstantiated()
    {
        $app = new Application;
        $this->assertInstanceOf(\Hope\Application::class, $app);
    }

    public function testHopeApplicationShouldInitiateDIContainer()
    {
        $x = test::double('Hope\Application');
        $app = new Application;
        $x->verifyInvoked('initContainer');
    }

    public function testIfDIIsRunningWell()
    {
        $app = new Application();

        $app->bind('TestFoo', function() {
            return 'Fooo';
        });

        $app->bind(DiExample::class, function() {
            return new DiExample;
        });

        $app->buildContainer();
        $foo = $app->get('TestFoo');

        $this->assertEquals('Fooo', $foo);

        $bar = $app->call('DiExample::exampleMethod', ['param1']);
        $this->assertEquals('example param1', $bar);
    }

    public function testBootstrapShouldCallRegisterProviders()
    {
        $xapp = test::double('Hope\Application');
        $app = new Application;
        $app->bootstrap();
        $xapp->verifyInvoked('registerProviders');
        $xapp->verifyInvoked('buildContainer');
        $xapp->verifyInvoked('registerRoutes');
    }

    public function testRegisterInvalidProviderShouldFail()
    {
        test::double('Hope\Application', ['loadProvidersList' => [DiExample::class]]);
        
        $this->tester->expectException(Hope\Exceptions\InvalidProviderException::class, function() {
            $app = new Application;
            $app->bootstrap();
        });
    }

    public function testDIOverloadContainer()
    {
        $app = new Application();

        $app->bind(DiExample::class, function() {
            return new DiExample;
        });

        $app->bind(DiExample::class, function () {
            return "Container Overloaded!";
        });

        $app->buildContainer();
        $content = $app->get('DiExample');

        $this->assertEquals('Container Overloaded!', $content);
    }

    public function testPerformResponseShouldWork()
    {
        test::double('Hope\Router\Dispatcher', ['dispatch' => ['foo' => 'bar']]);
        test::double('Hope\Outputer\OutputerJson', ['output' => function() {
            print 'foobar';
        }]);

        $this->expectOutputString('foobar');

        $app = new Application;
        $app->bootstrap();
        $app->run();
    }

    public function testPerformResponseShouldWorkWithHttpErrorException()
    {
        test::double('Hope\Router\Dispatcher', ['dispatch' => function () {
            throw new Hope\Exceptions\NotFoundException;
        }]);
        test::double('Hope\Outputer\OutputerJson', ['outputHttpError' => function() {
            print 'foobar http error';
        }]);

        $this->expectOutputString('foobar http error');

        $app = new Application;
        $app->bootstrap();
        $app->run();
    }

    public function testPerformResponseShouldWorkWithException()
    {
        test::double('Hope\Router\Dispatcher', ['dispatch' => function () {
            throw new \Exception('Error!');
        }]);
        test::double('Hope\Outputer\OutputerJson', ['outputException' => function() {
            print 'foobar exception';
        }]);

        $this->expectOutputString('foobar exception');

        $app = new Application;
        $app->bootstrap();
        $app->run();
    }

    public function testOutputerShouldNotHaveOutputIfNoDispatchOutput()
    {
        test::double('Hope\Router\Dispatcher', ['dispatch' => function () {}]);
        $mock = test::double('Hope\Outputer\OutputerJson');

        $this->expectOutputString(null);

        $app = new Application;
        $app->bootstrap();
        $app->run();
    }

    public function testApplicationConfig()
    {
        $config = Application::config();
        $this->assertInternalType('array', $config);
    }
}