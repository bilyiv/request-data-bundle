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
     * @return null|string
     */
    public function extractData(): ?string;

    /**
     * Detect and returns deserialization format.
     *
     * @return null|string
     */
    public function extractFormat(): ?string;

    /**
     * Returns supported deserialization formats.
     *
     * @return array
     */
    public function getSupportedFormats(): array;
}
