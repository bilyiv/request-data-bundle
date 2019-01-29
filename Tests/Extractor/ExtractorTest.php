<?php

namespace Bilyiv\RequestDataBundle\Tests\Extractor;

use Bilyiv\RequestDataBundle\Extractor\Extractor;
use Bilyiv\RequestDataBundle\Extractor\ExtractorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class ExtractorTest extends TestCase
{
    /**
     * @var RequestStack|MockObject
     */
    private $requestStack;

    /**
     * @var Extractor
     */
    private $extractor;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->requestStack = $this->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->extractor = new Extractor($this->requestStack);
    }

    /**
     * Test if extractor implements necessary interface.
     */
    public function testInterface()
    {
        $this->assertInstanceOf(ExtractorInterface::class, $this->extractor);
    }

    /**
     * Test if extractor extracts correct data from get request.
     */
    public function testExtractDataFromGetRequest()
    {
        $request = Request::create('/', Request::METHOD_GET, ['get' => 'request']);

        $this->requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->assertEquals('{"get":"request"}', $this->extractor->extractData());
    }

    /**
     * Test if extractor extracts correct data from post request.
     */
    public function testExtractDataFromPostRequest()
    {
        $request = Request::create('/', Request::METHOD_POST, [], [], [], [], '{"post":"request"}');

        $this->requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->assertEquals('{"post":"request"}', $this->extractor->extractData());
    }

    /**
     * Test if extractor extracts correct format from get request.
     */
    public function testExtractFormatFromGetRequest()
    {
        $request = Request::create('/', Request::METHOD_GET);

        $this->requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->assertEquals('json', $this->extractor->extractFormat());
    }

    /**
     * Test if extractor extracts correct format from post request.
     */
    public function testExtractFormatFromPostRequest()
    {
        $request = Request::create('/', Request::METHOD_POST);

        $this->requestStack
            ->expects($this->exactly(2))
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->assertNull($this->extractor->extractFormat());

        $request->headers->set('content-type', 'application/json');

        $this->assertEquals('json', $this->extractor->extractFormat());
    }
}
