<?php declare(strict_types=1);

namespace Swiftly\Template\Tests\Escape;

use PHPUnit\Framework\TestCase;
use Swiftly\Template\Escape\HtmlEscaper;

use function htmlentities;
use function method_exists;

use const ENT_COMPAT;
use const ENT_HTML5;
use const ENT_QUOTES;
use const ENT_XHTML;

class HtmlEscaperTest extends TestCase
{
    /** @var HtmlEscaper $escaper */
    private $escaper;

    /** @var string EXAMPLE_CONTENT */
    public const EXAMPLE_CONTENT = '<div class="test">Ex@mple "Content"</div>';

    protected function setUp(): void
    {
        $this->escaper = new HtmlEscaper(self::EXAMPLE_CONTENT);
    }

    public function testCanGetSchemeName(): void
    {
        self::assertSame('html', $this->escaper->name());
    }

    public function testCanEscapeContent(): void
    {
        self::assertSame(
            htmlentities(self::EXAMPLE_CONTENT, ENT_QUOTES | ENT_HTML5),
            $this->escaper->escape()
        );
    }

    public function testCanCastToString(): void
    {
        self::assertTrue(method_exists($this->escaper, '__toString'));
        self::assertSame(
            htmlentities(self::EXAMPLE_CONTENT, ENT_QUOTES | ENT_HTML5),
            (string)$this->escaper
        );
    }

    public function testCanConfigureEscapeFlags(): void
    {
        self::assertSame(
            htmlentities(self::EXAMPLE_CONTENT, ENT_COMPAT | ENT_XHTML),
            $this->escaper->with(ENT_COMPAT | ENT_XHTML)->escape()
        );
    }
}
