<?php

namespace Bilyiv\RequestDataBundle\Tests\Fixtures;

use Bilyiv\RequestDataBundle\OriginalDataInterface;
use Bilyiv\RequestDataBundle\OriginalDataTrait;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class TestRequestData implements OriginalDataInterface
{
    use OriginalDataTrait;

    /**
     * @var string|null
     */
    public $foo;
}
