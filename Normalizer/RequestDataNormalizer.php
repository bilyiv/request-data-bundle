<?php

namespace Bilyiv\RequestDataBundle\Normalizer;

use Bilyiv\RequestDataBundle\OriginalDataInterface;
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
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var ObjectNormalizer
     */
    private $normalizer;

    /**
     * @var TypeConverterInterface
     */
    private $converter;

    /**
     * @var string
     */
    private $prefix;

    public function __construct(
        RequestStack $requestStack,
        ObjectNormalizer $normalizer,
        TypeConverterInterface $converter,
        string $prefix
    ) {
        $this->requestStack = $requestStack;
        $this->normalizer = $normalizer;
        $this->converter = $converter;
        $this->prefix = $prefix;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $request = $this->requestStack->getCurrentRequest();

        if (Request::METHOD_GET === $request->getMethod()) {
            $data = $this->converter->convert($data);
        }

        $object = $this->normalizer->denormalize($data, $class, $format, $context);
        if ($object instanceof OriginalDataInterface) {
            $object->setOriginalData($data);
        }

        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return 0 === \strpos($type, $this->prefix) && \class_exists($type);
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return __CLASS__ === \get_class($this);
    }
}
