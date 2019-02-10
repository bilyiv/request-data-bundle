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
        $controller = $event->getController();
        if (!is_array($controller)) {
            return;
        }

        $controllerClass = new \ReflectionClass($controller[0]);
        if ($controllerClass->isAbstract()) {
            return;
        }

        $parameters = $controllerClass->getMethod($controller[1])->getParameters();
        foreach ($parameters as $parameter) {
            $class = $parameter->getClass();

            if (null !== $class && 0 === strpos($class->getName(), $this->prefix)) {
                $request = $event->getRequest();

                $format = $this->extractor->extractFormat($request);
                if (null === $format) {
                    break;
                }

                $data = $this->extractor->extractData($request, $format);
                if (!$data) {
                    break;
                }

                $requestData = $this->mapper->map($data, $format, $class->getName());

                $request->attributes->set($parameter->getName(), $requestData);

                $this->dispatcher->dispatch(Events::FINISH, new FinishEvent($requestData));

                break;
            }
        }
    }
}
