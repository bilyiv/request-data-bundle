<?php

namespace Bilyiv\RequestDataBundle\Tests\Extractor;

use Bilyiv\RequestDataBundle\Extractor\Extractor;
use Bilyiv\RequestDataBundle\Extractor\ExtractorInterface;
use Bilyiv\RequestDataBundle\Formats;
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
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->requestStack = $this->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Test if extractor implements necessary interface.
     */
    public function testInterface()
    {
        $request = Request::create('/', Request::METHOD_GET);

        $this->requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->assertInstanceOf(ExtractorInterface::class, new Extractor($this->requestStack));
    }

    /**
     * Test if extractor returns form data from GET request.
     */
    public function testGetFormDataFromGetRequest()
    {
        $request = Request::create('/', Request::METHOD_GET, ['get' => 'request']);

        $this->requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $extractor = new Extractor($this->requestStack);
        $this->assertEquals(['get' => 'request'], $extractor->getData());
    }

    /**
     * Test if extractor returns correct data from POST request.
     */
    public function testGetJsonDataFromPostRequest()
    {
        $request = Request::create('/', Request::METHOD_POST, [], [], [], [], '{"post":"request"}');
        $request->headers->set('content-type', 'application/json');

        $this->requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $extractor = new Extractor($this->requestStack);
        $this->assertEquals('{"post":"request"}', $extractor->getData());
    }

    /**
     * Test if extractor returns json data from PUT request.
     */
    public function testGetJsonDataFromPutRequest()
    {
        $request = Request::create('/', Request::METHOD_POST, [], [], [], [], '{"put":"request"}');
        $request->headers->set('content-type', 'application/json');

        $this->requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $extractor = new Extractor($this->requestStack);
        $this->assertEquals('{"put":"request"}', $extractor->getData());
    }

    /**
     * Test if extractor returns json data from PATCH request.
     */
    public function testGetJsonDataFromPatchRequest()
    {
        $request = Request::create('/', Request::METHOD_PATCH, [], [], [], [], '{"patch":"request"}');
        $request->headers->set('content-type', 'application/json');

        $this->requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $extractor = new Extractor($this->requestStack);
        $this->assertEquals('{"patch":"request"}', $extractor->getData());
    }

    /**
     * Test if extractor returns form format from GET request.
     */
    public function testGetFormFormatFromGetRequest()
    {
        $request = Request::create('/', Request::METHOD_GET);

        $this->requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $extractor = new Extractor($this->requestStack);
        $this->assertEquals(Formats::FORM, $extractor->getFormat());
    }

    /**
     * Test if extractor returns form format from POST request.
     */
    public function testGetFormFormatFromPostRequest()
    {
        $request = Request::create('/', Request::METHOD_POST);

        $this->requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $extractor = new Extractor($this->requestStack);
        $this->assertEquals(Formats::FORM, $extractor->getFormat());
    }

    /**
     * Test if extractor returns form format from PUT request.
     */
    public function testGetFormFormatFromPutRequest()
    {
        $request = Request::create('/', Request::METHOD_PUT);

        $this->requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $extractor = new Extractor($this->requestStack);
        $this->assertEquals(Formats::FORM, $extractor->getFormat());
    }

    /**
     * Test if extractor returns json format from POST request.
     */
    public function testGetJsonFormatFromPostRequest()
    {
        $request = Request::create('/', Request::METHOD_POST);
        $request->headers->set('content-type', 'application/json');

        $this->requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $extractor = new Extractor($this->requestStack);
        $this->assertEquals(Formats::JSON, $extractor->getFormat());
    }

    /**
     * Test if extractor returns json format from PUT request.
     */
    public function testGetJsonFormatFromPutRequest()
    {
        $request = Request::create('/', Request::METHOD_PUT);
        $request->headers->set('content-type', 'application/json');

        $this->requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $extractor = new Extractor($this->requestStack);
        $this->assertEquals(Formats::JSON, $extractor->getFormat());
    }

    /**
     * Test if extractor returns json format from PATCH request.
     */
    public function testGetJsonFormatFromPatchRequest()
    {
        $request = Request::create('/', Request::METHOD_PATCH);
        $request->headers->set('content-type', 'application/json');

        $this->requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $extractor = new Extractor($this->requestStack);
        $this->assertEquals(Formats::JSON, $extractor->getFormat());
    }
}
