<?php

namespace tests\unit\EsSandbox\Common\Model;

class EnumTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_constructs_valid_value()
    {
        new ExampleEnum(ExampleEnum::ONE);
    }

    /**
     * @test
     * @expectedException \UnexpectedValueException
     */
    public function it_fails_if_constructed_with_invalid_value()
    {
        new ExampleEnum(-1);
    }

    /**
     * @test
     * @expectedException \UnexpectedValueException
     */
    public function it_fails_if_constructed_with_value_of_wrong_type()
    {
        new ExampleEnum('1');
    }

    /**
     * @test
     */
    public function it_can_be_constructed_by_factory_method()
    {
        $enum = ExampleEnum::ONE();

        $this->assertEquals(new ExampleEnum(ExampleEnum::ONE), $enum);
    }

    /**
     * @test
     * @expectedException \BadMethodCallException
     */
    public function it_cannot_be_constructed_when_invalid_factory_method_is_used()
    {
        ExampleEnum::NON_EXISTENT();
    }

    /**
     * @test
     */
    public function it_gets_value()
    {
        $enum = ExampleEnum::ONE();

        $this->assertEquals(ExampleEnum::ONE, $enum->get());
        $this->assertNotEquals(ExampleEnum::TWO, $enum->get());
    }

    /**
     * @test
     */
    public function it_can_be_string()
    {
        $enum = ExampleEnum::ONE();

        $this->assertEquals(ExampleEnum::ONE, (string) $enum);
    }

    /**
     * @test
     */
    public function it_has_equality()
    {
        $enum = ExampleEnum::ONE();

        $this->assertTrue($enum->equals(ExampleEnum::ONE()));
        $this->assertFalse($enum->equals(ExampleEnum::TWO()));
        $this->assertFalse($enum->equals(ExampleEnum::THREE()));
    }
}
