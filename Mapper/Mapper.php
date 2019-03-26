<?php

namespace Bilyiv\RequestDataBundle\Mapper;

use Bilyiv\RequestDataBundle\Exception\NotSupportedFormatException;
use Bilyiv\RequestDataBundle\Extractor\ExtractorInterface;
use Bilyiv\RequestDataBundle\Formats;
use Bilyiv\RequestDataBundle\FormatSupportableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class Mapper implements MapperInterface
{
    /**
     * @var ExtractorInterface
     */
    private $extractor;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    public function __construct(
        ExtractorInterface $extractor,
        SerializerInterface $serializer,
        PropertyAccessorInterface $propertyAccessor
    ) {
        $this->extractor = $extractor;
        $this->serializer = $serializer;
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * {@inheritdoc}
     */
    public function map(Request $request, object $object): void
    {
        $format = $this->extractor->extractFormat($request);
        $formatSupportable = $object instanceof FormatSupportableInterface;
        if (!$format || ($formatSupportable && !\in_array($format, $object::getSupportedFormats()))) {
            throw new NotSupportedFormatException();
        }

        $data = $this->extractor->extractData($request, $format);
        if (!$data) {
            return;
        }

        if (Formats::FORM === $format && \is_array($data)) {
            $this->mapForm($data, $object);
            return;
        }

        $this->serializer->deserialize($data, \get_class($object), $format, ['object_to_populate' => $object]);
    }

    /**
     * @param array  $data
     * @param object $object
     */
    protected function mapForm(array $data, object $object): void
    {
        foreach ($data as $propertyPath => $propertyValue) {
            if ($this->propertyAccessor->isWritable($object, $propertyPath)) {
                $this->propertyAccessor->setValue($object, $propertyPath, $propertyValue);
            }
        }
    }
}
