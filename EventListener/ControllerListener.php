<?php

namespace Bilyiv\RequestDataBundle\EventListener;

use Bilyiv\RequestDataBundle\Event\FinishEvent;
use Bilyiv\RequestDataBundle\Events;
use Bilyiv\RequestDataBundle\Extractor\ExtractorInterface;
use Bilyiv\RequestDataBundle\Mapper\MapperInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class ControllerListener
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var ExtractorInterface
     */
    private $extractor;

    /**
     * @var MapperInterface
     */
    private $mapper;

    /**
     * @var string
     */
    private $prefix;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        ExtractorInterface $extractor,
        MapperInterface $mapper,
        string $prefix
    ) {
        $this->dispatcher = $dispatcher;
        $this->extractor = $extractor;
        $this->mapper = $mapper;
        $this->prefix = $prefix;
    }

    /**
     * @param FilterControllerEvent $event
     *
     * @throws \ReflectionException
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $format = $this->extractor->getFormat();
        if (!$format) {
            return;
        }

        $data = $this->extractor->getData();
        if (!$data) {
            return;
        }

        $controller = $event->getController();
        if (!is_array($controller)) {
            return;
        }

        $controllerClass = (new \ReflectionClass($controller[0]));
        if ($controllerClass->isAbstract()) {
            return;
        }

        $parameters = $controllerClass->getMethod($controller[1])->getParameters();
        foreach ($parameters as $parameter) {
            $class = $parameter->getClass();

            if ($class && 0 === strpos($class->getName(), $this->prefix)) {
                $requestData = $this->mapper->map($data, $format, $class->getName());

                $event->getRequest()->attributes->set($parameter->getName(), $requestData);

                $this->dispatcher->dispatch(Events::FINISH, new FinishEvent($requestData));

                break;
            }
        }
    }
}
