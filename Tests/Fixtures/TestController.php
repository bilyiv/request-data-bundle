<?php

namespace Bilyiv\RequestDataBundle\Tests\Fixtures;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class TestController extends TestAbstractController
{
    public function index(Request $request)
    {
        return null;
    }
}
