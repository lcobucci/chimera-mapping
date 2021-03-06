<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\Mapping\Tests\Unit\Routing;

use Doctrine\Common\Annotations\AnnotationException;
use Lcobucci\Chimera\Mapping\Routing\FetchEndpoint;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Lcobucci\Chimera\Mapping\Routing\FetchEndpoint
 */
final class FetchEndpointTest extends TestCase
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
        $annotation = new FetchEndpoint(self::ENDPOINT_DATA + ['query' => 'testing']);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->query);
        self::assertSame(['GET'], $annotation->methods);
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
        $annotation = new FetchEndpoint(self::ENDPOINT_DATA + $values);

        $this->expectException(AnnotationException::class);
        $annotation->validate('class A');
    }

    /**
     * @return mixed[][]
     */
    public function invalidScenarios(): array
    {
        return [
            'empty query'      => [[]],
            'non-string query' => [['query' => false]],
        ];
    }
}
