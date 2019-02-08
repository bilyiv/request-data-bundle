<?php

namespace Bilyiv\RequestDataBundle\Mapper;

use Bilyiv\RequestDataBundle\Formats;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class Mapper implements MapperInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    public function __construct(SerializerInterface $serializer, PropertyAccessorInterface $propertyAccessor)
    {
        $this->serializer = $serializer;
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * {@inheritdoc}
     */
    public function map($data, string $format, string $class): object
    {
        if (Formats::FORM === $format && is_array($data)) {
            return $this->mapFormFormat($data, $class);
        }

        return $this->serializer->deserialize($data, $class, $format);
    }

    /**
     * @param array  $data
     * @param string $class
     *
     * @return object
     */
    protected function mapFormFormat(array $data, string $class): object
    {
        $object = new $class();

        foreach ($data as $propertyPath => $propertyValue) {
            $this->propertyAccessor->setValue($object, $propertyPath, $propertyValue);
        }

        return $object;
    }
}
