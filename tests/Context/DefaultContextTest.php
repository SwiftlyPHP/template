<?php declare(strict_types=1);

namespace Swiftly\Template\Tests\Context;

use PHPUnit\Framework\TestCase;
use Swiftly\Template\Context\DefaultContext;

use function dirname;

class DefaultContextTest extends TestCase
{
    /** @var string $templates */
    private $templates;

    /** @var DefaultContext $context */
    private $context;

    protected function setUp(): void
    {
        $this->templates = dirname(__DIR__) . '/templates';

        $this->context = new DefaultContext();
    }

    public function testCanContextWrapFile(): callable
    {
        $wrap = $this->context->wrap("{$this->templates}/simple.php");

        self::assertIsCallable($wrap);

        return $wrap;
    }

    /** @depends testCanContextWrapFile */
    public function testCanRenderTemplate(callable $wrap): void
    {
        $result = $wrap([ 'name' => 'John' ]);

        self::assertSame('My name is: John', $result);
    }
}
