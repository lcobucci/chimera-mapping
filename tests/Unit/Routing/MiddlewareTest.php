<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\Mapping\Tests\Unit\Routing;

use Doctrine\Common\Annotations\AnnotationException;
use Lcobucci\Chimera\Mapping\Routing\Middleware;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Lcobucci\Chimera\Mapping\Routing\Middleware
 */
final class MiddlewareTest extends TestCase
{
    /**
     * @test
     *
     * @covers ::__construct()
     * @covers ::validate()
     * @covers \Lcobucci\Chimera\Mapping\Validator
     */
    public function validateShouldNotRaiseExceptionsWhenStateIsValid(): void
    {
        $annotation = new Middleware(['path' => '/tests', 'app' => 'testing', 'priority' => 10]);
        $annotation->validate('class A');

        self::assertSame('/tests', $annotation->path);
        self::assertSame('testing', $annotation->app);
        self::assertSame(10, $annotation->priority);
    }

    /**
     * @test
     * @dataProvider invalidScenarios
     *
     * @covers ::__construct()
     * @covers ::validate()
     * @covers \Lcobucci\Chimera\Mapping\Validator
     *
     * @param mixed[] $values
     */
    public function validateShouldRaiseExceptionWhenInvalidDataWasProvided(array $values): void
    {
        $annotation = new Middleware($values);

        $this->expectException(AnnotationException::class);
        $annotation->validate('class A');
    }

    /**
     * @return mixed[][]
     */
    public function invalidScenarios(): array
    {
        return [
            'non-string path'  => [['path' => false]],
            'non-string value' => [['path' => false]],
            'non-string app'   => [['app' => false]],
            'non-int priority' => [['priority' => false]],
        ];
    }
}
