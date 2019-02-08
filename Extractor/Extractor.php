<?php

namespace Bilyiv\RequestDataBundle\Extractor;

use Bilyiv\RequestDataBundle\Formats;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class Extractor implements ExtractorInterface
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var string|null
     */
    private $format;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->format = $this->extractFormat();
        $this->data = $this->extractData();
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
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
    public function getSupportedFormats(): array
    {
        return [Formats::JSON, Formats::FORM];
    }

    /**
     * Extract data from current request.
     */
    protected function extractData()
    {
        $method = $this->request->getMethod();

        if (Formats::FORM === $this->getFormat()) {
            if (Request::METHOD_GET === $method) {
                return $this->request->query->all();
            }

            return $this->request->files->all() + $this->request->request->all();
        }

        if (Request::METHOD_POST === $method || Request::METHOD_PUT === $method || Request::METHOD_PATCH === $method) {
            return $this->request->getContent();
        }

        return null;
    }

    /**
     * Extract format from current request.
     */
    protected function extractFormat(): ?string
    {
        if (Request::METHOD_GET === $this->request->getMethod()) {
            return Formats::FORM;
        }

        $format = $this->request->getFormat($this->request->headers->get('content-type'));

        if (!in_array($format, $this->getSupportedFormats())) {
            return null;
        }

        return $format;
    }
}
