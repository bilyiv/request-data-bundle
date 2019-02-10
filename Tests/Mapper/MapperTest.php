<?php

namespace Bilyiv\RequestDataBundle\Tests\Extractor;

use Bilyiv\RequestDataBundle\Formats;
use Bilyiv\RequestDataBundle\Mapper\Mapper;
use Bilyiv\RequestDataBundle\Mapper\MapperInterface;
use Bilyiv\RequestDataBundle\Tests\Fixtures\TestRequestData;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class MapperTest extends TestCase
{
    /**
     * @var MapperInterface
     */
    private $mapper;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $serializer = $this->getMockBuilder(SerializerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $requestData = new TestRequestData();
        $requestData->foo = 'bar';

        $serializer
            ->method('deserialize')
            ->willReturn($requestData);

        $this->mapper = new Mapper($serializer, new PropertyAccessor());
    }

    /**
     * Test if mapper implements necessary interface.
     */
    public function testInterface()
    {
        $this->assertInstanceOf(MapperInterface::class, $this->mapper);
    }

    /**
     * Test if mapper maps form data correctly.
     */
    public function testFormDataMap()
    {
        $requestData = $this->mapper->map(['foo' => 'bar'], Formats::FORM, TestRequestData::class);

        $this->assertInstanceOf(TestRequestData::class, $requestData);
        $this->assertEquals('bar', $requestData->foo);
    }

    /**
     * Test if mapper maps json data correctly.
     */
    public function testJsonDataMap()
    {
        $requestData = $this->mapper->map('{"foo":"bar"}', Formats::JSON, TestRequestData::class);

        $this->assertInstanceOf(TestRequestData::class, $requestData);
        $this->assertEquals('bar', $requestData->foo);
    }

    /**
     * Test if mapper maps xml data correctly.
     */
    public function testXmlDataMap()
    {
        $requestData = $this->mapper->map('<request><foo>bar</foo></request>', Formats::XML, TestRequestData::class);

        $this->assertInstanceOf(TestRequestData::class, $requestData);
        $this->assertEquals('bar', $requestData->foo);
    }
}
