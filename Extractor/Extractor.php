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
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function extractData(): ?string
    {
        $request = $this->requestStack->getCurrentRequest();

        switch ($request->getMethod()) {
            case Request::METHOD_GET:
                if ($query = $request->query->all()) {
                    return \json_encode($query);
                }
                break;

            case Request::METHOD_POST:
            case Request::METHOD_PUT:
            case Request::METHOD_PATCH:
                return $request->getContent();
                break;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function extractFormat(): ?string
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request->getMethod() === Request::METHOD_GET) {
            return 'json';
        }

        $format = $request->getFormat($request->headers->get('content-type'));

        if (!in_array($format, $this->getSupportedFormats())) {
            return null;
        }

        return $format;
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedFormats(): array
    {
        return ['json', 'xml', 'csv'];
    }
}
