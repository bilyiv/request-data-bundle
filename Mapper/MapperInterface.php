<?php

namespace Bilyiv\RequestDataBundle\Mapper;

use Bilyiv\RequestDataBundle\Exception\NotSupportedFormatException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
interface MapperInterface
{
    /**
     * Map request to certain object.
     *
     * @param Request $request
     * @param object  $object
     *
     * @throws NotSupportedFormatException
     */
    public function map(Request $request, object $object): void;
}
