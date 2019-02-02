<?php

namespace Bilyiv\RequestDataBundle\Tests\Fixtures;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class TestRequestDataController extends TestAbstractController
{
    public function index(TestRequestData $data)
    {
        return $data;
    }
}
