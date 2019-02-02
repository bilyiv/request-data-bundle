<?php

namespace Bilyiv\RequestDataBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class FinishEvent extends Event
{
    /**
     * @var object
     */
    protected $requestData;

    public function __construct(object $requestData)
    {
        $this->requestData = $requestData;
    }

    /**
     * @return object
     */
    public function getRequestData(): object
    {
        return $this->requestData;
    }
}
