<?php

namespace Bilyiv\RequestDataBundle\Tests\Extractor;

use Bilyiv\RequestDataBundle\Extractor\Extractor;
use Bilyiv\RequestDataBundle\Extractor\ExtractorInterface;
use Bilyiv\RequestDataBundle\Formats;
use Bilyiv\RequestDataBundle\TypeConverter\TypeConverterInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class ExtractorTest extends TestCase
{
    /**
     * @var ExtractorInterface
     */
    private $extractor;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $converter = $this->getMockBuilder(TypeConverterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $converter
            ->method('convert')
            ->willReturn(['get' => 'request']);

        $this->extractor = new Extractor($converter);
    }

    /**
     * Test if extractor implements necessary interface.
     */
    public function testInterface()
    {
        $this->assertInstanceOf(ExtractorInterface::class, $this->extractor);
    }

    /**
     * Test if extractor returns form data from GET request.
     */
    public function testExtractFormDataFromGetRequest()
    {
        $request = Request::create('/', Request::METHOD_GET, ['get' => 'request']);

        $this->assertEquals(['get' => 'request'], $this->extractor->extractData($request, Formats::FORM));
    }

    /**
     * Test if extractor returns correct data from POST request.
     */
    public function testExtractJsonDataFromPostRequest()
    {
        $request = Request::create('/', Request::METHOD_POST, [], [], [], [], '{"post":"request"}');
        $request->headers->set('content-type', 'application/json');

        $this->assertEquals('{"post":"request"}', $this->extractor->extractData($request, Formats::JSON));
    }

    /**
     * Test if extractor returns json data from PUT request.
     */
    public function testExtractJsonDataFromPutRequest()
    {
        $request = Request::create('/', Request::METHOD_POST, [], [], [], [], '{"put":"request"}');
        $request->headers->set('content-type', 'application/json');

        $this->assertEquals('{"put":"request"}', $this->extractor->extractData($request, Formats::JSON));
    }

    /**
     * Test if extractor returns json data from PATCH request.
     */
    public function testExtractJsonDataFromPatchRequest()
    {
        $request = Request::create('/', Request::METHOD_PATCH, [], [], [], [], '{"patch":"request"}');
        $request->headers->set('content-type', 'application/json');

        $this->assertEquals('{"patch":"request"}', $this->extractor->extractData($request, Formats::JSON));
    }

    /**
     * Test if extractor returns form format from GET request.
     */
    public function testExtractFormFormatFromGetRequest()
    {
        $request = Request::create('/', Request::METHOD_GET);

        $this->assertEquals(Formats::FORM, $this->extractor->extractFormat($request));
    }

    /**
     * Test if extractor returns form format from POST request.
     */
    public function testExtractFormFormatFromPostRequest()
    {
        $request = Request::create('/', Request::METHOD_POST);

        $this->assertEquals(Formats::FORM, $this->extractor->extractFormat($request));
    }

    /**
     * Test if extractor returns form format from PUT request.
     */
    public function testExtractFormFormatFromPutRequest()
    {
        $request = Request::create('/', Request::METHOD_PUT);

        $this->assertEquals(Formats::FORM, $this->extractor->extractFormat($request));
    }

    /**
     * Test if extractor returns json format from POST request.
     */
    public function testExtractJsonFormatFromPostRequest()
    {
        $request = Request::create('/', Request::METHOD_POST);
        $request->headers->set('content-type', 'application/json');

        $this->assertEquals(Formats::JSON, $this->extractor->extractFormat($request));
    }

    /**
     * Test if extractor returns json format from PUT request.
     */
    public function testExtractJsonFormatFromPutRequest()
    {
        $request = Request::create('/', Request::METHOD_PUT);
        $request->headers->set('content-type', 'application/json');

        $this->assertEquals(Formats::JSON, $this->extractor->extractFormat($request));
    }

    /**
     * Test if extractor returns json format from PATCH request.
     */
    public function testExtractJsonFormatFromPatchRequest()
    {
        $request = Request::create('/', Request::METHOD_PATCH);
        $request->headers->set('content-type', 'application/json');

        $this->assertEquals(Formats::JSON, $this->extractor->extractFormat($request));
    }
}
