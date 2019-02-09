<?php

namespace Bilyiv\RequestDataBundle\Extractor;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
interface ExtractorInterface
{
    /**
     * Extract data from the request.
     *
     * @param Request $request
     * @param string  $format
     *
     * @return mixed
     */
    public function extractData(Request $request, string $format);

    /**
     * Extract format from the request.
     *
     * @param Request $request
     *
     * @return string|null
     */
    public function extractFormat(Request $request): ?string;

    /**
     * Return supported formats.
     *
     * @return array
     */
    public function getSupportedFormats(): array;
}
