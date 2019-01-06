<?php

namespace Bilyiv\RequestDataBundle;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
interface EntityFactoryInterface
{
    /**
     * @param null|object $existed
     * @return object
     */
    public function entity(?object $existed = null): object;
}