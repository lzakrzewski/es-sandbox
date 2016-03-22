<?php

namespace tests\unit\EsSandbox\Common\Model\Identifier;

use tests\unit\EsSandbox\Common\Model\Identifier\Fixtures\TestStringId1;
use tests\unit\EsSandbox\Common\Model\Identifier\Fixtures\TestStringId2;

class StringIdentifierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_should_prevent_instantiation_from_integer()
    {
        TestStringId1::of(123);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_should_prevent_instantiation_from_null()
    {
        TestStringId1::of(null);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_should_prevent_instantiation_from_empty_value()
    {
        TestStringId1::of('');
    }

    /**
     * @test
     */
    public function it_recreates_itself_from_string_representation()
    {
        $id = TestStringId1::of('abc');

        $stringRepresentation = (string) $id;

        $recreatedId = TestStringId1::fromString($stringRepresentation);

        $this->assertEquals($id, $recreatedId);
    }

    /**
     * @test
     */
    public function it_has_equality()
    {
        $id          = TestStringId1::of('abc');
        $sameId      = TestStringId1::of('abc');
        $differentId = TestStringId1::of('xyz');

        $this->assertTrue($id->equals($sameId));
        $this->assertFalse($id->equals($differentId));
    }

    /**
     * @test
     */
    public function it_is_not_equal_to_different_type_with_the_same_value()
    {
        $id          = TestStringId1::of('abc');
        $otherTypeId = TestStringId2::of('abc');

        $this->assertFalse($id->equals($otherTypeId));
    }

    /**
     * @test
     */
    public function it_returns_raw_value()
    {
        $id = TestStringId1::of('abc');

        $this->assertEquals('abc', $id->raw());
    }
}
