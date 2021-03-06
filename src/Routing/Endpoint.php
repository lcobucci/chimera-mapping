<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\Mapping\Routing;

use Doctrine\Common\Annotations\AnnotationException;
use Lcobucci\Chimera\Mapping\Annotation;
use Lcobucci\Chimera\Mapping\Validator;
use function get_class;

abstract class Endpoint implements Annotation
{
    private const ALLOWED_METHODS = ['GET', 'POST', 'DELETE', 'PATCH', 'PUT', 'OPTIONS', 'HEAD'];

    /**
     * @var string
     */
    public $path;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string|null
     */
    public $app;

    /**
     * @var string[]
     */
    public $methods;

    /**
     * @param mixed[] $values
     */
    public function __construct(array $values)
    {
        $this->path    = $values['path'] ?? $values['value'] ?? null;
        $this->name    = $values['name'] ?? null;
        $this->app     = $values['app'] ?? null;
        $this->methods = $values['methods'] ?? $this->defaultMethods();
    }

    /**
     * {@inheritdoc}
     */
    public function validate(string $context): void
    {
        $validator = new Validator(get_class($this), $context);
        $validator->requiredScalar('path', 'string', $this->path);
        $validator->requiredScalar('name', 'string', $this->name);
        $validator->nonRequiredScalar('app', 'string', $this->app);
        $validator->enumArray('methods', self::ALLOWED_METHODS, $this->methods);

        $this->validateAdditionalData($validator);
    }

    /**
     * @throws AnnotationException
     */
    abstract protected function validateAdditionalData(Validator $validator): void;

    /**
     * @return string[]
     */
    abstract protected function defaultMethods(): array;
}
