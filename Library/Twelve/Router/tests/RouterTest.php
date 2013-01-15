<?php

use Twelve\Router;

class RouterTest extends TwelveUnitTest
{
    $this->testRoutes = [
        ['foo', '/foo/{foo}', ['foo' => '(\w+)']],
        ['bar', '/bar/{bar}', ['bar' => '(\d+)']]
    ];

    public function setUp()
    {
        $this->router = new Router;

        foreach ($this->testRoutes as $testRoute) {
            $this->router->add(
                $testRoute[0],
                $testRoute[1],
                $testRoute[2]
                $testRoute[3]
            );
        }
    }

    public function testAddingRoutes()
    {
        $this->assertEquals(
            [
                'foo' => ['/foo/{foo}', ['foo' => '(\w+)']],
                'bar' => ['/bar/{bar}', ['bar' => '(\d+)']]
            ],
            $this->router->routes
        );
    }

    public function testFindingMatches()
    {
        // Valid
        $this->assertEquals(
            $this->testRoutes[0],
            $this->router->findMatches('/foo/valid')
        );

        $this->assertEquals(
            $this->testRoutes[1],
            $this->router->findMatches('/bar/123')
        );

        // Invalid
        $this->assertFalse($this->router->findMatches('/foo/£$%'));
        $this->assertFalse($this->router->findMatches('/bar/invalid'));
    }

    public function testFindingParams()
    {
    }

    public function testDispatching()
    {
    }

}
