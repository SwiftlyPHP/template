<?php

namespace Swiftly\Template\Tests\Context;

use PHPUnit\Framework\TestCase;
use Swiftly\Template\Context\HelperContext;
use Swiftly\Template\Escape\HtmlEscaper;
use Swiftly\Template\Escape\JsonEscaper;
use Swiftly\Template\EscapeInterface;
use Swiftly\Template\Exception\UnknownSchemeException;

use function dirname;
use function htmlentities;
use function json_encode;
use function get_class;

use const ENT_QUOTES;
use const ENT_HTML5;
use const JSON_PRESERVE_ZERO_FRACTION;

Class HelperContextTest Extends TestCase
{

    /** @var string $templates */
    private $templates;

    /** @var HelperContext $context */
    private $context;

    protected function setUp() : void
    {
        $this->templates = dirname( __DIR__ ) . '/templates';

        $this->context = new HelperContext();
    }

    public function testCanContextWrapFile() : callable
    {
        $wrap = $this->context->wrap( "{$this->templates}/simple.php" );

        self::assertIsCallable( $wrap );

        return $wrap;
    }

    /** @depends testCanContextWrapFile */
    public function testCanRenderTemplate( callable $wrap ) : void
    {
        $result = $wrap([ 'name' => 'John' ]);

        self::assertSame( 'My name is: John', $result );
    }

    /** @depends testCanRenderTemplate */
    public function testCanEscapeFromWithinTemplate() : void
    {
        $wrap = $this->context->wrap( "{$this->templates}/advanced.php" );

        $result = $wrap([ 'html' => '<div id="test"></div>' ]);

        self::assertSame(
            htmlentities( '<div id="test"></div>', ENT_QUOTES | ENT_HTML5 ),
            $result
        );
    }

    public function testCanEscapeContentAsHtml() : void
    {
        $html = $this->context->escapeHtml( '<div id="test"></div>' );

        self::assertInstanceOf( HtmlEscaper::class, $html );
        self::assertSame(
            htmlentities( '<div id="test"></div>', ENT_QUOTES | ENT_HTML5 ),
            (string)$html
        );
    }

    public function testCanEscapeContentAsJson() : void
    {
        $json = $this->context->escapeJson([ 'name' => 'John' ]);

        self::assertInstanceOf( JsonEscaper::class, $json );
        self::assertSame(
            json_encode([ 'name' => 'John' ], JSON_PRESERVE_ZERO_FRACTION ),
            (string)$json
        );
    }

    public function testCanRegisterCustomEscapeScheme() : void
    {
        $scheme = $this->createMock( EscapeInterface::class );

        $context = new HelperContext([
            'scheme' => get_class( $scheme )
        ]);

        $escaper = $context->escape( 'scheme', 'some_content' );

        $escaper->expects( $this->once() )
            ->method( '__toString' )
            ->willReturn( 'some_content' );

        self::assertInstanceOf( get_class( $scheme ), $escaper );
        self::assertSame( 'some_content', (string)$escaper );
    }

    public function testThrowsOnUnknownEscapeScheme() : void
    {
        self::expectException( UnknownSchemeException::class );

        $this->context->escape( 'unknown', 'some_content' );
    }
}
