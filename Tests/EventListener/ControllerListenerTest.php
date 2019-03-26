<?php

namespace Bilyiv\RequestDataBundle\Tests\EventListener;

use Bilyiv\RequestDataBundle\EventListener\ControllerListener;
use Bilyiv\RequestDataBundle\Mapper\MapperInterface;
use Bilyiv\RequestDataBundle\Tests\Fixtures\TestAbstractController;
use Bilyiv\RequestDataBundle\Tests\Fixtures\TestController;
use Bilyiv\RequestDataBundle\Tests\Fixtures\TestRequestData;
use Bilyiv\RequestDataBundle\Tests\Fixtures\TestRequestDataController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class ControllerListenerTest extends TestCase
{
    /**
     * @var ControllerListener
     */
    private $controllerListener;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $mapper = $this->getMockBuilder(MapperInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mapper
            ->method('map')
            ->willReturnCallback(function (Request $request, object $object) {
                $object->foo = 'bar';
            });

        $dispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->controllerListener = new ControllerListener(
            $mapper,
            $dispatcher,
            'Bilyiv\RequestDataBundle\Tests\Fixtures'
        );
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

        $filterControllerEvent
            ->expects($this->never())
            ->method('getRequest');

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

        $filterControllerEvent
            ->expects($this->never())
            ->method('getRequest');

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

        $result = $this->controllerListener->onKernelController($filterControllerEvent);

        $this->assertNull($result);
        $this->assertEquals(1, $request->attributes->count());
        $this->assertInstanceOf(TestRequestData::class, $request->attributes->get('data'));
        $this->assertEquals('bar', $request->attributes->get('data')->foo);
    }
}
