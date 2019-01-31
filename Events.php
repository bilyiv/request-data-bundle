<?php

namespace Bilyiv\RequestDataBundle;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
final class Events
{
    /**
     * The FINISH event occurs when a request data was deserialized.
     *
     * @Event("Bilyiv\RequestDataBundle\Event\DeserializedEvent")
     */
    const DESERIALIZED = 'request_data.deserialized';
}