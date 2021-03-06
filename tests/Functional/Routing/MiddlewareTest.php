<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\Mapping\Tests\Functional\Routing;

use Lcobucci\Chimera\Mapping\Routing\Middleware;
use Lcobucci\Chimera\Mapping\Tests\Functional\TestCase;
use function assert;

final class MiddlewareTest extends TestCase
{
    /**
     * @test
     *
     * @covers \Lcobucci\Chimera\Mapping\Routing\Middleware
     * @covers \Lcobucci\Chimera\Mapping\Reader
     */
    public function defaultValueShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(HttpMiddleware1::class, Middleware::class);
        assert($annotation instanceof Middleware || $annotation === null);

        self::assertInstanceOf(Middleware::class, $annotation);
        self::assertSame('/testing', $annotation->path);
        self::assertSame('my-app', $annotation->app);
        self::assertSame(1, $annotation->priority);
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Chimera\Mapping\Routing\Middleware
     * @covers \Lcobucci\Chimera\Mapping\Reader
     */
    public function propertiesShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(HttpMiddleware2::class, Middleware::class);
        assert($annotation instanceof Middleware || $annotation === null);

        self::assertInstanceOf(Middleware::class, $annotation);
        self::assertSame('/testing', $annotation->path);
        self::assertSame('my-app', $annotation->app);
        self::assertSame(1, $annotation->priority);
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Chimera\Mapping\Routing\Middleware
     * @covers \Lcobucci\Chimera\Mapping\Reader
     */
    public function everythingShouldBeFineIfNoValueWasProvided(): void
    {
        $annotation = $this->readAnnotation(HttpMiddleware3::class, Middleware::class);
        assert($annotation instanceof Middleware || $annotation === null);

        self::assertInstanceOf(Middleware::class, $annotation);
        self::assertSame('/', $annotation->path);
        self::assertNull($annotation->app);
        self::assertSame(0, $annotation->priority);
    }
}

/**
 * @Middleware("/testing", priority=1, app="my-app")
 */
final class HttpMiddleware1
{
}

/**
 * @Middleware(path="/testing", priority=1, app="my-app")
 */
final class HttpMiddleware2
{
}

/**
 * @Middleware
 */
final class HttpMiddleware3
{
}
