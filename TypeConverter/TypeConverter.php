<?php

namespace Bilyiv\RequestDataBundle\TypeConverter;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class TypeConverter implements TypeConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function convert($value)
    {
        if (\is_string($value)) {
            $value = $this->convertString($value);
        }

        if (\is_array($value)) {
            $value = $this->convertArray($value);
        }

        return $value;
    }

    /**
     * @param array $values
     *
     * @return array
     */
    protected function convertArray(array $values)
    {
        foreach ($values as &$value) {
            $value = $this->convert($value);
        }

        return $values;
    }

    /**
     * @param string $value
     *
     * @return mixed
     */
    protected function convertString(string $value)
    {
        if ('' === $value || 'null' === $value) {
            return null;
        }
        if ('true' === $value) {
            return true;
        }
        if ('false' === $value) {
            return false;
        }
        if (\preg_match('/^-?\d+$/', $value)) {
            return (int) $value;
        }
        if (\preg_match('/^-?\d+(\.\d+)?$/', $value)) {
            return (float) $value;
        }

        return $value;
    }
}
