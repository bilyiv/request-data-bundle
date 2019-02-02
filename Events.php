<?php

namespace Bilyiv\RequestDataBundle;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
final class Events
{
    /**
     * The FINISH event occurs when a request data is deserialized.
     *
     * @Event("Bilyiv\RequestDataBundle\Event\FinishEvent")
     */
    const FINISH = 'request_data.finish';
}