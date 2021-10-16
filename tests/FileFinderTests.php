<?php

namespace Swiftly\Template\Tests;

use PHPUnit\Framework\TestCase;
use Swiftly\Template\FileFinder;

use function dirname;

Class FileFinderTests Extends TestCase
{

    /** @var string $root */
    private $root;

    protected function setUp() : void
    {
        $this->root = dirname( __DIR__ );
    }

    public function testCanFindFileInSingleDirectory() : void
    {
        $src = "{$this->root}/src";

        $finder = new FileFinder( $src );

        $file1 = $finder->find( 'Engine.php' );
        $file2 = $finder->find( 'FileFinder.php' );
        $file3 = $finder->find( 'Escape/JsonEscaper.php' );

        self::assertSame( "$src/Engine.php", $file1 );
        self::assertSame( "$src/FileFinder.php", $file2 );
        self::assertSame( "$src/Escape/JsonEscaper.php", $file3 );
    }

    public function testCanFindFileOverMultipleDirectories() : void
    {
        $src = "{$this->root}/src";
        $tests = "{$this->root}/tests";

        $finder = new FileFinder([ $src, $tests ]);

        $file1 = $finder->find( 'Engine.php' );
        $file2 = $finder->find( 'FileFinderTests.php' );
        $file3 = $finder->find( 'Context/DefaultContextTests.php' );

        self::assertSame( "$src/Engine.php", $file1 );
        self::assertSame( "$tests/FileFinderTests.php", $file2 );
        self::assertSame( "$tests/Context/DefaultContextTests.php", $file3 );
    }

    public function testReturnsNullWhenNotFound() : void
    {
        $src = "{$this->root}/src";
        $tests = "{$this->root}/tests";

        $finder = new FileFinder([ $src, $tests ]);

        self::assertNull( $finder->find( 'NotARealFile.php' ) );
        self::assertNull( $finder->find( 'FinderFile.php' ) );
        self::assertNull( $finder->find( 'HHG/42.php' ) );
    }
}
