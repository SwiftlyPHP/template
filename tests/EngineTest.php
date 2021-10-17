<?php

namespace Swiftly\Template\Tests;

use PHPUnit\Framework\TestCase;
use Swiftly\Template\Engine;
use Swiftly\Template\FileFinder;
use Swiftly\Template\ContextInterface;
use Swiftly\Template\Exception\MissingTemplateException;

Class EngineTest Extends TestCase
{

    public function testCanRenderTemplate() : void
    {
        $finder = $this->createMock( FileFinder::class );
        $finder->expects( $this->once() )
            ->method( 'find' )
            ->with( 'example.php' )
            ->willReturn( 'templates/example.php' );

        $context = $this->createMock( ContextInterface::class );
        $context->expects( $this->once() )
            ->method( 'wrap' )
            ->with( 'templates/example.php' )
            ->willReturn(
                function ( array $variables ) : string {
                    self::assertSame( [ 'name' => 'John' ], $variables );
                    return 'content';
                }
            );

        $engine = new Engine( $finder, $context );

        $result = $engine->render( 'example.php', [ 'name' => 'John' ] );

        self::assertSame( 'content', $result );
    }

    public function testThrowsOnMissingTemplate() : void
    {
        $finder = $this->createMock( FileFinder::class );
        $finder->expects( $this->once() )
            ->method( 'find' )
            ->willReturn( null );

        $context = $this->createMock( ContextInterface::class );

        $engine = new Engine( $finder, $context );

        self::expectException( MissingTemplateException::class );

        $engine->render( 'example.php' );
    }
}
