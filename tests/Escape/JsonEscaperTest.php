<?php

namespace Swiftly\Template\Tests\Escape;

use PHPUnit\Framework\TestCase;
use Swiftly\Template\Escape\JsonEscaper;
use Swiftly\Template\Exception\EscapeException;
use stdClass;
use Throwable;

use function json_encode;

use const JSON_PRESERVE_ZERO_FRACTION;
use const JSON_HEX_AMP;

Class JsonEscaperTest Extends TestCase
{

    /** @var JsonEscaper $escaper */
    private $escaper;

    /** @var array EXAMPLE_CONTENT */
    const EXAMPLE_CONTENT = [
        'example' => 'some_string',
        'nested' => [
            'id' => 42.0,
            'email' => 'John&Smith@example.com'
        ]
    ];

    protected function setUp() : void
    {
        $this->escaper = new JsonEscaper( self::EXAMPLE_CONTENT );
    }

    public function testCanGetSchemeName() : void
    {
        self::assertSame( 'json', $this->escaper->name() );
    }

    public function testCanEscapeContents() : void
    {
        self::assertSame(
            json_encode( self::EXAMPLE_CONTENT, JSON_PRESERVE_ZERO_FRACTION ),
            $this->escaper->escape()
        );
    }

    public function testCanCastToString() : void
    {
        self::assertTrue( method_exists( $this->escaper, '__toString' ) );
        self::assertSame(
            json_encode( self::EXAMPLE_CONTENT, JSON_PRESERVE_ZERO_FRACTION ),
            (string)$this->escaper
        );
    }

    public function testCanConfigureEscapeFlags() : void
    {
        self::assertSame(
            json_encode( self::EXAMPLE_CONTENT, JSON_HEX_AMP ),
            $this->escaper->with( JSON_HEX_AMP )->escape()
        );
    }

    public function testCanPrettyPrintContents() : void
    {
        self::assertSame(
            json_encode(
                self::EXAMPLE_CONTENT,
                JSON_PRESERVE_ZERO_FRACTION | JSON_PRETTY_PRINT
            ),
            $this->escaper->pretty()->escape()
        );
    }

    public function testThrowsOnInvalidInput() : void
    {
        self::expectException( EscapeException::class );

        // Json can't do references or recursion
        $example1 = new stdClass;
        $example2 = new stdClass;

        $example1->ref = $example2;
        $example2->ref = $example1;

        ( new JsonEscaper( $example1 ) )->escape();
    }
}
