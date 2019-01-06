<?php

namespace Bilyiv\RequestDataBundle\EventListener;

use Bilyiv\RequestDataBundle\Exception\DeserializationException;
use Bilyiv\RequestDataBundle\Exception\ValidationException;
use Bilyiv\RequestDataBundle\Extractor\ExtractorInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class ControllerListener
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var ExtractorInterface
     */
    private $extractor;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, ExtractorInterface $extractor) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->extractor = $extractor;
    }

    /**
     * @param FilterControllerEvent $event
     * @throws DeserializationException
     * @throws ValidationException
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
            if (!$class || strpos($class->getName(), 'App\\RequestData\\') !== 0) {
                continue;
            }

            try {
                $requestData = $this->serializer->deserialize($data, $class->getName(), $format);
            } catch (\Exception $exception) {
                throw new DeserializationException('Request data schema is not valid', 0, $exception);
            }

            $errors = $this->validator->validate($requestData);
            if ($errors->count() !== 0) {
                throw new ValidationException($errors, 'Request data is not valid');
            }

            $event->getRequest()->attributes->set($parameter->getName(), $requestData);
        }
    }
}
