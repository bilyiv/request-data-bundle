<?php

namespace Bilyiv\RequestDataBundle\Normalizer;

use Bilyiv\RequestDataBundle\OriginalDataTrait;
use Bilyiv\RequestDataBundle\TypeConverter\TypeConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class RequestDataNormalizer implements DenormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var ObjectNormalizer
     */
    private $normalizer;

    /**
     * @var TypeConverterInterface
     */
    private $converter;

    public function __construct(RequestStack $requestStack, ObjectNormalizer $normalizer, TypeConverterInterface $converter)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->normalizer = $normalizer;
        $this->converter = $converter;
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if ($this->request->getMethod() === Request::METHOD_GET) {
            $data = $this->converter->convert($data);
        }

        $object = $this->normalizer->denormalize($data, $class, $format, $context);
        if ($object instanceof OriginalDataTrait) {
            $object->setOriginalData($data);
        }

        return $object;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return \strpos($type, 'App\\RequestData\\') === 0;
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return __CLASS__ === \get_class($this);
    }
}