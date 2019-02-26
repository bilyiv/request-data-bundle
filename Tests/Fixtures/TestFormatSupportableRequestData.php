<?php

namespace Bilyiv\RequestDataBundle\Tests\Fixtures;

use Bilyiv\RequestDataBundle\Formats;
use Bilyiv\RequestDataBundle\FormatSupportableInterface;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class TestFormatSupportableRequestData implements FormatSupportableInterface
{
    /**
     * @var string|null
     */
    public $foo;

    /**
     * {@inheritdoc}
     */
    public static function getSupportedFormats(): array
    {
        return [Formats::JSON];
    }
}
