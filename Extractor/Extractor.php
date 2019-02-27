<?php

namespace Bilyiv\RequestDataBundle\Extractor;

use Bilyiv\RequestDataBundle\Formats;
use Bilyiv\RequestDataBundle\TypeConverter\TypeConverterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class Extractor implements ExtractorInterface
{
    /**
     * @var TypeConverterInterface
     */
    private $converter;

    public function __construct(TypeConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    /**
     * {@inheritdoc}
     */
    public function extractData(Request $request, string $format)
    {
        $method = $request->getMethod();

        if (Request::METHOD_GET === $method) {
            return $this->converter->convert($request->query->all());
        }

        if (Request::METHOD_POST === $method || Request::METHOD_PUT === $method || Request::METHOD_PATCH === $method) {
            if (Formats::FORM === $format) {
                return $request->files->all() + $request->request->all();
            }

            return $request->getContent();
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function extractFormat(Request $request): ?string
    {
        if (Request::METHOD_GET === $request->getMethod()) {
            return Formats::FORM;
        }

        $format = $request->getFormat($request->headers->get('content-type'));

        if (!\in_array($format, static::getSupportedFormats())) {
            return null;
        }

        return $format;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSupportedFormats(): array
    {
        return [Formats::FORM, Formats::JSON, Formats::XML];
    }
}
