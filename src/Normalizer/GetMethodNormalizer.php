<?php

namespace Bilyiv\RequestDataBundle\Normalizer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class GetMethodNormalizer implements DenormalizerInterface
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var ObjectNormalizer
     */
    private $normalizer;

    public function __construct(RequestStack $requestStack, ObjectNormalizer $normalizer)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->normalizer = $normalizer;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return
            strpos($type, 'App\\RequestData\\') === 0 &&
            is_array($data) &&
            $this->request->getMethod() == Request::METHOD_GET;
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        foreach ($data as $key => $value) {
            if (\is_string($value) === false) {
                continue;
            }

            if ($value === 'true') {
                $data[$key] = true;
            } elseif ($value === 'false') {
                $data[$key] = false;
            } elseif (preg_match('/^-?\d+$/', $value)) {
                $data[$key] = (int)$value;
            } elseif (preg_match('/^-?\d+(\.\d+)?$/', $value)) {
                $data[$key] = (float)$value;
            }
        }

        return $this->normalizer->denormalize($data, $class, $format, $context);
    }
}