<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\Mapping\Tests\Functional\Routing;

use Doctrine\Common\Annotations\AnnotationException;
use Lcobucci\Chimera\Mapping\Routing\CreateEndpoint;
use Lcobucci\Chimera\Mapping\Tests\Functional\TestCase;
use function assert;

final class CreateEndpointTest extends TestCase
{
    /**
     * @test
     *
     * @covers \Lcobucci\Chimera\Mapping\Routing\Endpoint
     * @covers \Lcobucci\Chimera\Mapping\Routing\CreateEndpoint
     * @covers \Lcobucci\Chimera\Mapping\Reader
     */
    public function defaultValueShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(AddBookToCollectionHandler::class, CreateEndpoint::class);
        assert($annotation instanceof CreateEndpoint || $annotation === null);

        self::assertInstanceOf(CreateEndpoint::class, $annotation);
        self::assertSame('/books', $annotation->path);
        self::assertSame(AddBookToCollection::class, $annotation->command);
        self::assertSame(['POST'], $annotation->methods);
        self::assertSame('books.create', $annotation->name);
        self::assertSame('books.fetch', $annotation->redirectTo);
        self::assertFalse($annotation->async);
        self::assertNull($annotation->app);
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Chimera\Mapping\Routing\Endpoint
     * @covers \Lcobucci\Chimera\Mapping\Routing\CreateEndpoint
     * @covers \Lcobucci\Chimera\Mapping\Reader
     */
    public function propertiesShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(RegisterAuthorHandler::class, CreateEndpoint::class);
        assert($annotation instanceof CreateEndpoint || $annotation === null);

        self::assertInstanceOf(CreateEndpoint::class, $annotation);
        self::assertSame('/authors', $annotation->path);
        self::assertSame(RegisterAuthor::class, $annotation->command);
        self::assertSame(['POST'], $annotation->methods);
        self::assertSame('authors.create', $annotation->name);
        self::assertSame('authors.fetch', $annotation->redirectTo);
        self::assertTrue($annotation->async);
        self::assertSame('my-app', $annotation->app);
    }

    /**
     * @test
     *
     * @covers \Lcobucci\Chimera\Mapping\Routing\Endpoint
     * @covers \Lcobucci\Chimera\Mapping\Routing\CreateEndpoint
     * @covers \Lcobucci\Chimera\Mapping\Reader
     */
    public function exceptionShouldBeRaisedWhenRequiredPropertiesAreMissing(): void
    {
        $this->expectException(AnnotationException::class);
        $this->readAnnotation(RegisterSomethingNew::class, CreateEndpoint::class);
    }
}

final class AddBookToCollection
{
}

final class RegisterAuthor
{
}

/**
 * @CreateEndpoint("/books", command=AddBookToCollection::class, name="books.create", redirectTo="books.fetch")
*/
final class AddBookToCollectionHandler
{
}

/**
 * @CreateEndpoint(
 *     path="/authors",
 *     command=RegisterAuthor::class,
 *     name="authors.create",
 *     app="my-app",
 *     async=true,
 *     redirectTo="authors.fetch"
 * )
 */
final class RegisterAuthorHandler
{
}

/**
 * @CreateEndpoint
 */
final class RegisterSomethingNew
{
}
