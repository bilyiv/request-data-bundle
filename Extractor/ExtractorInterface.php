<?php

namespace Bilyiv\RequestDataBundle\Extractor;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
interface ExtractorInterface
{
    /**
     * Detect and returns deserialization data.
     *
     * @return string|null
     */
    public function extractData(): ?string;

    /**
     * Detect and returns deserialization format.
     *
     * @return string|null
     */
    public function extractFormat(): ?string;

    /**
     * Returns supported deserialization formats.
     *
     * @return array
     */
    public function getSupportedFormats(): array;
}
