<?php

namespace Bilyiv\RequestDataBundle\TypeConverter;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
interface TypeConverterInterface
{
    /**
     * @param $value
     *
     * @return mixed
     */
    public function convert($value);
}
