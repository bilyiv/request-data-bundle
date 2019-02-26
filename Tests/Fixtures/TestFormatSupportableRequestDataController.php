<?php

namespace Bilyiv\RequestDataBundle\Tests\Fixtures;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class TestFormatSupportableRequestDataController extends TestAbstractController
{
    public function index(TestFormatSupportableRequestData $data)
    {
        return $data;
    }
}
