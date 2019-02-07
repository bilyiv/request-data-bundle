<?php

namespace Bilyiv\RequestDataBundle\Extractor;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
interface ExtractorInterface
{
    /**
     * Detect and return data.
     *
     * @return mixed
     */
    public function getData();

    /**
     * Detect and return format.
     *
     * @return string|null
     */
    public function getFormat(): ?string;

    /**
     * Return supported formats.
     *
     * @return array
     */
    public function getSupportedFormats(): array;
}
