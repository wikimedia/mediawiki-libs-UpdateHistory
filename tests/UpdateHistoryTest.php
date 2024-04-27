<?php

namespace Wikimedia\UpdateHistory\Tests;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Wikimedia\UpdateHistory\UpdateHistory;

/**
 * @covers \Wikimedia\UpdateHistory\UpdateHistory
 */
class UpdateHistoryTest extends TestCase {

	private const INPUT = "
## foo-bar 1.0.0

* Initial release.";

	private const NOT_YET_RELEASED = "
## foo-bar x.x.x (not yet released)
" . self::INPUT;

	public function testUpdateHistory(): void {
		$this->assertEquals(
			self::NOT_YET_RELEASED,
			UpdateHistory::addChangelogEntry( self::INPUT )
		);

		$date = date( 'Y-m-d' );

		$patch = "
## foo-bar 1.0.1 ($date)
" . self::INPUT;

		$this->assertEquals(
			$patch,
			UpdateHistory::addChangelogEntry( self::NOT_YET_RELEASED, 'patch' )
		);

		$minor = "
## foo-bar 1.1.0 ($date)
" . self::INPUT;

		$this->assertEquals(
			$minor,
			UpdateHistory::addChangelogEntry( self::NOT_YET_RELEASED, 'minor' )
		);

		$major = "
## foo-bar 2.0.0 ($date)
" . self::INPUT;

		$this->assertEquals(
			$major,
			UpdateHistory::addChangelogEntry( self::NOT_YET_RELEASED, 'major' )
		);
	}

	public function testBadWhich(): void {
		$this->expectException( RuntimeException::class );
		UpdateHistory::addChangelogEntry( self::NOT_YET_RELEASED, 'foo' );
	}

	public function testLastVersionNotFound(): void {
		$this->expectException( RuntimeException::class );
		UpdateHistory::addChangelogEntry( '', 'foo' );
	}
}
