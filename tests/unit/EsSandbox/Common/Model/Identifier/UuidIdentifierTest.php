<?php

namespace tests\unit\EsSandbox\Common\Model\Identifier;

use Ramsey\Uuid\Uuid;
use tests\unit\EsSandbox\Common\Model\Identifier\Fixtures\TestUuid1;
use tests\unit\EsSandbox\Common\Model\Identifier\Fixtures\TestUuid2;

class UuidIdentifierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_generates_valid_uuid()
    {
        $id = TestUuid1::generate();

        $this->assertTrue(Uuid::isValid((string) $id));
    }

    /**
     * @test
     */
    public function it_recreates_itself_from_string_representation()
    {
        $id = TestUuid1::of(Uuid::uuid4());

        $stringRepresentation = (string) $id;

        $recreatedId = TestUuid1::fromString($stringRepresentation);

        $this->assertEquals($id, $recreatedId);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_fails_if_string_is_invalid_uuid()
    {
        TestUuid1::fromString('invalid-uuid');
    }

    /**
     * @test
     */
    public function it_has_equality()
    {
        $id          = TestUuid1::fromString('ff6f8cb0-c57d-11e1-9b21-0800200c9a66');
        $sameId      = TestUuid1::fromString('ff6f8cb0-c57d-11e1-9b21-0800200c9a66');
        $differentId = TestUuid1::fromString('02d9e6d5-9467-382e-8f9b-9300a64ac3cd');

        $this->assertTrue($id->equals($sameId));
        $this->assertFalse($id->equals($differentId));
    }

    /**
     * @test
     */
    public function it_is_not_equal_to_different_type_with_the_same_value()
    {
        $id          = TestUuid1::fromString('ff6f8cb0-c57d-11e1-9b21-0800200c9a66');
        $otherTypeId = TestUuid2::fromString('ff6f8cb0-c57d-11e1-9b21-0800200c9a66');

        $this->assertFalse($id->equals($otherTypeId));
    }

    /**
     * @test
     */
    public function it_returns_raw_value()
    {
        $uuid = Uuid::uuid4();

        $this->assertTrue(TestUuid1::of($uuid)->raw()->equals($uuid));
    }
}
