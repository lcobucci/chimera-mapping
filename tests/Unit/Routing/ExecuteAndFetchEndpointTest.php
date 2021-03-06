<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\Mapping\Tests\Unit\Routing;

use Doctrine\Common\Annotations\AnnotationException;
use Lcobucci\Chimera\Mapping\Routing\ExecuteAndFetchEndpoint;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Lcobucci\Chimera\Mapping\Routing\ExecuteAndFetchEndpoint
 */
final class ExecuteAndFetchEndpointTest extends TestCase
{
    private const ENDPOINT_DATA = ['path' => '/tests', 'name' => 'test'];

    /**
     * @test
     *
     * @covers ::__construct()
     * @covers ::validateAdditionalData()
     * @covers ::defaultMethods()
     * @covers \Lcobucci\Chimera\Mapping\Validator
     * @covers \Lcobucci\Chimera\Mapping\Routing\Endpoint
     */
    public function validateShouldNotRaiseExceptionsWhenStateIsValid(): void
    {
        $annotation = new ExecuteAndFetchEndpoint(self::ENDPOINT_DATA + ['command' => 'testing', 'query' => 'test']);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->command);
        self::assertSame('test', $annotation->query);
        self::assertSame(['PUT', 'PATCH'], $annotation->methods);
    }

    /**
     * @test
     * @dataProvider invalidScenarios
     *
     * @covers ::__construct()
     * @covers ::validateAdditionalData()
     * @covers ::defaultMethods()
     * @covers \Lcobucci\Chimera\Mapping\Validator
     * @covers \Lcobucci\Chimera\Mapping\Routing\Endpoint
     *
     * @param mixed[] $values
     */
    public function validateShouldRaiseExceptionWhenInvalidDataWasProvided(array $values): void
    {
        $annotation = new ExecuteAndFetchEndpoint(self::ENDPOINT_DATA + $values);

        $this->expectException(AnnotationException::class);
        $annotation->validate('class A');
    }

    /**
     * @return mixed[][]
     */
    public function invalidScenarios(): array
    {
        return [
            'empty command'      => [['query' => 'test']],
            'non-string command' => [['command' => false, 'query' => 'test']],
            'empty query'        => [['command' => 'test']],
            'non-string query'   => [['query' => false, 'command' => 'test']],
        ];
    }
}
