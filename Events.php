<?php

namespace Bilyiv\RequestDataBundle;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
final class Events
{
    /**
     * The FINISH event occurs when request data formation is finished.
     *
     * @Event("Bilyiv\RequestDataBundle\Event\FinishEvent")
     */
    public const FINISH = 'request_data.finish';
}
