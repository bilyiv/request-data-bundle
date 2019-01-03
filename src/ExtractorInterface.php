<?php

namespace Bilyiv\RequestDataBundle;

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
    public function getData(): ?string;

    /**
     * Detect and returns deserialization format.
     *
     * @return null|string
     */
    public function getFormat(): ?string;

    /**
     * Returns supported deserialization formats.
     *
     * @return array
     */
    public static function getSupportedFormats(): array;

    /**
     * Adds a new supported deserialization format.
     *
     * @param string $format
     */
    public static function addSupportedFormat(string $format);
}
