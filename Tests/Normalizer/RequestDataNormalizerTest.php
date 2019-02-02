<?php

namespace Bilyiv\RequestDataBundle\Tests\Normalizer;

use Bilyiv\RequestDataBundle\Normalizer\RequestDataNormalizer;
use Bilyiv\RequestDataBundle\Tests\Fixtures\TestRequestData;
use Bilyiv\RequestDataBundle\TypeConverter\TypeConverter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class RequestDataNormalizerTest extends TestCase
{
    /**
     * @var RequestDataNormalizer
     */
    private $normalizer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $requestStack = $this->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->getMock();

        $requestStack
            ->method('getCurrentRequest')
            ->willReturn(new Request());

        $testRequestData = new TestRequestData();
        $testRequestData->foo = 'bar';

        $objectNormalizer = $this->getMockBuilder(ObjectNormalizer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $objectNormalizer
            ->method('denormalize')
            ->willReturn($testRequestData);

        $this->normalizer = new RequestDataNormalizer(
            $requestStack,
            $objectNormalizer,
            new TypeConverter(),
            'Bilyiv\RequestDataBundle\Tests\Fixtures'
        );
    }

    /**
     * Test if normalizer implements necessary interface.
     */
    public function testInterface()
    {
        $this->assertInstanceOf(DenormalizerInterface::class, $this->normalizer);
        $this->assertInstanceOf(CacheableSupportsMethodInterface::class, $this->normalizer);
    }

    /**
     * Test if normalizer supports correct classes.
     */
    public function testSupportsDenormalization()
    {
        $this->assertFalse($this->normalizer->supportsDenormalization(null, \stdClass::class));
        $this->assertTrue($this->normalizer->supportsDenormalization(null, TestRequestData::class));
    }

    /**
     * Test if normalizer denormalizes data correctly.
     */
    public function testDenormalize()
    {
        $object = $this->normalizer->denormalize(['foo' => 'bar'], TestRequestData::class);

        $this->assertEquals($object->foo, 'bar');
        $this->assertEquals(['foo' => 'bar'], $object->getOriginalData());
    }
}
