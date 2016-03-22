<?php

namespace tests\unit\EsSandbox\Common\Model\Identifier;

use tests\unit\EsSandbox\Common\Model\Identifier\Fixtures\TestIntegerId1;
use tests\unit\EsSandbox\Common\Model\Identifier\Fixtures\TestIntegerId2;

class IntegerIdentifierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_should_prevent_instantiation_from_string()
    {
        TestIntegerId1::of('abc');
    }

    /**
     * @test
     */
    public function it_recreates_itself_from_string_representation()
    {
        $id = TestIntegerId1::of(123);

        $stringRepresentation = (string) $id;

        $recreatedId = TestIntegerId1::fromString($stringRepresentation);

        $this->assertEquals($id, $recreatedId);
    }

    /**
     * @test
     */
    public function it_has_equality()
    {
        $id          = TestIntegerId1::of(123);
        $sameId      = TestIntegerId1::of(123);
        $differentId = TestIntegerId1::of(321);

        $this->assertTrue($id->equals($sameId));
        $this->assertFalse($id->equals($differentId));
    }

    /**
     * @test
     */
    public function it_is_not_equal_to_different_type_with_the_same_value()
    {
        $id          = TestIntegerId1::of(123);
        $otherTypeId = TestIntegerId2::of(123);

        $this->assertFalse($id->equals($otherTypeId));
    }

    /**
     * @test
     */
    public function it_returns_raw_value()
    {
        $this->assertSame(123, TestIntegerId1::of(123)->raw());
    }
}
