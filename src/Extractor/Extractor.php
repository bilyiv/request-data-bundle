<?php

namespace Bilyiv\RequestDataBundle\Extractor;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class Extractor implements ExtractorInterface
{
    /**
     * @var array
     */
    public static $supportedFormats = ['json', 'xml', 'csv'];

    /**
     * @var Request
     */
    private $request;

    /**
     * @var null|string
     */
    private $data;

    /**
     * @var null|string
     */
    private $format;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();

        $this->extractData();
        $this->extractFormat();
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): ?string
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormat(): ?string
    {
        return $this->format;

    }

    /**
     * {@inheritdoc}
     */
    public static function getSupportedFormats(): array
    {
        return self::$supportedFormats;
    }

    /**
     * {@inheritdoc}
     */
    public static function addSupportedFormat(string $format)
    {
        self::$supportedFormats[] = $format;
    }

    /**
     * Extract data from request.
     */
    protected function extractData()
    {
        switch ($this->request->getMethod()) {
            case Request::METHOD_GET:
                if ($query = $this->request->query->all()) {
                    $this->data = \json_encode($query);
                }
                break;

            case Request::METHOD_POST:
            case Request::METHOD_PUT:
            case Request::METHOD_PATCH:
                $this->data = $this->request->getContent();
                break;
        }
    }

    /**
     * Extract format from request.
     */
    protected function extractFormat()
    {
        $format = $this->request->getFormat($this->request->headers->get('content-type'));

        if ($this->request->getMethod() === Request::METHOD_GET) {
            $format = 'json';
        }

        if (\in_array($format, static::$supportedFormats)) {
            $this->format = $format;
        }
    }
}
