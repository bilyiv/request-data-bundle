<?php

namespace Bilyiv\RequestDataBundle\Tests\EventListener;

use Bilyiv\RequestDataBundle\EventListener\ControllerListener;
use Bilyiv\RequestDataBundle\Extractor\ExtractorInterface;
use Bilyiv\RequestDataBundle\Tests\Fixtures\TestAbstractController;
use Bilyiv\RequestDataBundle\Tests\Fixtures\TestController;
use Bilyiv\RequestDataBundle\Tests\Fixtures\TestRequestData;
use Bilyiv\RequestDataBundle\Tests\Fixtures\TestRequestDataController;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class ControllerListenerTest extends TestCase
{
    /**
     * @var ExtractorInterface|MockObject
     */
    private $extractor;

    /**
     * @var ControllerListener
     */
    private $controllerListener;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $testRequestData = new TestRequestData();
        $testRequestData->foo = 'bar';

        $serializer = $this->getMockBuilder(SerializerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $serializer
            ->method('deserialize')
            ->willReturn($testRequestData);

        $dispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->extractor = $this->getMockBuilder(ExtractorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->controllerListener = new ControllerListener(
            $serializer,
            $dispatcher,
            $this->extractor,
            'Bilyiv\RequestDataBundle\Tests\Fixtures'
        );
    }

    /**
     * Tests if listener do nothing when there is no format.
     */
    public function testOnKernelControllerWithoutRequestFormat()
    {
        $filterControllerEvent = $this->getMockBuilder(FilterControllerEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->extractor
            ->expects($this->once())
            ->method('extractFormat')
            ->willReturn(null);

        $result = $this->controllerListener->onKernelController($filterControllerEvent);

        $this->assertNull($result);
    }

    /**
     * Tests if listener do nothing when there is no data.
     */
    public function testOnKernelControllerWithoutRequestData()
    {
        $filterControllerEvent = $this->getMockBuilder(FilterControllerEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->extractor
            ->expects($this->once())
            ->method('extractFormat')
            ->willReturn('json');

        $this->extractor
            ->expects($this->once())
            ->method('extractData')
            ->willReturn(null);

        $result = $this->controllerListener->onKernelController($filterControllerEvent);

        $this->assertNull($result);
    }

    /**
     * Tests if listener do nothing when there is wrong controller.
     */
    public function testOnKernelControllerWithWrongController()
    {
        $filterControllerEvent = $this->getMockBuilder(FilterControllerEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $filterControllerEvent
            ->expects($this->once())
            ->method('getController')
            ->willReturn(null);

        $this->extractor
            ->expects($this->once())
            ->method('extractFormat')
            ->willReturn('json');

        $this->extractor
            ->expects($this->once())
            ->method('extractData')
            ->willReturn('{"post": "data"}');

        $result = $this->controllerListener->onKernelController($filterControllerEvent);

        $this->assertNull($result);
    }

    /**
     * Tests if listener do nothing when there is an abstract controller.
     */
    public function testOnKernelControllerWithAbstractController()
    {
        $filterControllerEvent = $this->getMockBuilder(FilterControllerEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $filterControllerEvent
            ->expects($this->once())
            ->method('getController')
            ->willReturn([TestAbstractController::class]);

        $this->extractor
            ->expects($this->once())
            ->method('extractFormat')
            ->willReturn('json');

        $this->extractor
            ->expects($this->once())
            ->method('extractData')
            ->willReturn('{"post": "data"}');

        $result = $this->controllerListener->onKernelController($filterControllerEvent);

        $this->assertNull($result);
    }

    /**
     * Tests if listener do nothing when there is a controller without injected request data class.
     */
    public function testOnKernelControllerWithoutInjectedRequestData()
    {
        $filterControllerEvent = $this->getMockBuilder(FilterControllerEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $filterControllerEvent
            ->expects($this->once())
            ->method('getController')
            ->willReturn([TestController::class, 'index']);

        $filterControllerEvent
            ->expects($this->never())
            ->method('getRequest');

        $this->extractor
            ->expects($this->once())
            ->method('extractFormat')
            ->willReturn('json');

        $this->extractor
            ->expects($this->once())
            ->method('extractData')
            ->willReturn('{"post": "data"}');

        $result = $this->controllerListener->onKernelController($filterControllerEvent);

        $this->assertNull($result);
    }

    /**
     * Tests if listener do nothing when there is a controller with injected request data class.
     */
    public function testOnKernelControllerWithInjectedRequestData()
    {
        $request = new Request();

        $filterControllerEvent = $this->getMockBuilder(FilterControllerEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $filterControllerEvent
            ->expects($this->once())
            ->method('getController')
            ->willReturn([TestRequestDataController::class, 'index']);

        $filterControllerEvent
            ->expects($this->once())
            ->method('getRequest')
            ->willReturn($request);

        $this->extractor
            ->expects($this->once())
            ->method('extractFormat')
            ->willReturn('json');

        $this->extractor
            ->expects($this->once())
            ->method('extractData')
            ->willReturn('{"post": "data"}');

        $result = $this->controllerListener->onKernelController($filterControllerEvent);

        $this->assertNull($result);
        $this->assertEquals(1, $request->attributes->count());
        $this->assertInstanceOf(TestRequestData::class, $request->attributes->get('data'));
        $this->assertEquals('bar', $request->attributes->get('data')->foo);
    }
}
