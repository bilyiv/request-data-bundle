<?php

namespace Bilyiv\RequestDataBundle\Mapper;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
interface MapperInterface
{
    /**
     * Map data to certain class.
     *
     * @param $data
     * @param string $format
     * @param string $class
     *
     * @return object
     */
    public function map($data, string $format, string $class): object;
}
