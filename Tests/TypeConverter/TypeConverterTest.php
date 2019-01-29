<?php

namespace Bilyiv\RequestDataBundle\Tests\TypeConverter;

use Bilyiv\RequestDataBundle\TypeConverter\TypeConverter;
use Bilyiv\RequestDataBundle\TypeConverter\TypeConverterInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class TypeConverterTest extends TestCase
{
    /**
     * @var TypeConverter
     */
    private $converter;

    public function setUp()
    {
        $this->converter = new TypeConverter();
    }

    public function testInterface()
    {
        $this->assertInstanceOf(TypeConverterInterface::class, $this->converter);
    }

    public function testConvertScalars()
    {
        $this->assertNull($this->converter->convert(''));
        $this->assertNull($this->converter->convert('null'));
        $this->assertTrue($this->converter->convert('true'));
        $this->assertFalse($this->converter->convert('false'));
        $this->assertIsInt($this->converter->convert('10'));
        $this->assertIsFloat($this->converter->convert('10.1'));
    }

    public function testConvertArray()
    {
        $this->assertEquals([null, 10.1], $this->converter->convert(['', '10.1']));
        $this->assertEquals([10 => [true]], $this->converter->convert([10 => ['true']]));
    }
}
