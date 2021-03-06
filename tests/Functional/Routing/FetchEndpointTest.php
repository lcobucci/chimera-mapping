<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\Mapping\Tests\Functional\Routing;

use Doctrine\Common\Annotations\AnnotationException;
use Lcobucci\Chimera\Mapping\Routing\FetchEndpoint;
use Lcobucci\Chimera\Mapping\Tests\Functional\TestCase;
use function assert;

final class FetchEndpointTest extends TestCase
{
    /**
     * @test
     *
     * @covers \Lcobucci\Chimera\Mapping\Routing\Endpoint
     * @covers \Lcobucci\Chimera\Mapping\Routing\FetchEndpoint
     * @covers \Lcobucci\Chimera\Mapping\Reader
     */
    public function defaultValueShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(FetchBookHandler::class, FetchEndpoint::class);
        assert($annotation instanceof FetchEndpoint || $annotation === null);

        self::assertInstanceOf(FetchEndpoint::class, $annotation);
        self::assertSame('/books/{id}', $annotation->path);
        self::assertSame(FetchBook::class, $annotation->query);
        self::assertSame(['GET'], $annotation->methods);
        self::assertSame('books.fetch', $annotation->name);
        self::assertNull($annotation->app);
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Chimera\Mapping\Routing\Endpoint
     * @covers \Lcobucci\Chimera\Mapping\Routing\FetchEndpoint
     * @covers \Lcobucci\Chimera\Mapping\Reader
     */
    public function propertiesShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(FindBooksHandler::class, FetchEndpoint::class);
        assert($annotation instanceof FetchEndpoint || $annotation === null);

        self::assertInstanceOf(FetchEndpoint::class, $annotation);
        self::assertSame('/books', $annotation->path);
        self::assertSame(FindBooks::class, $annotation->query);
        self::assertSame(['GET'], $annotation->methods);
        self::assertSame('books.find', $annotation->name);
        self::assertSame('my-app', $annotation->app);
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Chimera\Mapping\Routing\Endpoint
     * @covers \Lcobucci\Chimera\Mapping\Routing\FetchEndpoint
     * @covers \Lcobucci\Chimera\Mapping\Reader
     */
    public function exceptionShouldBeRaisedWhenRequiredPropertiesAreMissing(): void
    {
        $this->expectException(AnnotationException::class);
        $this->readAnnotation(FindAuthorsHandler::class, FetchEndpoint::class);
    }
}

final class FetchBook
{
}

final class FindBooks
{
}

/**
 * @FetchEndpoint("/books/{id}", query=FetchBook::class, name="books.fetch")
*/
final class FetchBookHandler
{
}

/**
 * @FetchEndpoint(path="/books", query=FindBooks::class, name="books.find", app="my-app")
 */
final class FindBooksHandler
{
}

/**
 * @FetchEndpoint
 */
final class FindAuthorsHandler
{
}
