<?php

namespace Wikimedia\UpdateHistory\Tests;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Wikimedia\UpdateHistory\UpdateHistory;

/**
 * @covers \Wikimedia\UpdateHistory\UpdateHistory
 */
class UpdateHistoryTest extends TestCase {

	private const INPUT = <<<INPUT
## foo-bar 1.0.0

* Initial release.
INPUT;

	public function testUpdateHistory(): void {
		$patch = <<<INPUT
## foo-bar x.x.x (not yet released)

## foo-bar 1.0.0

* Initial release.
INPUT;

		$this->assertEquals(
			$patch,
			UpdateHistory::addChangelogEntry( self::INPUT, 'patch' )
		);
	}

	public function testLastVersionNotFound(): void {
		$this->expectException( RuntimeException::class );
		UpdateHistory::addChangelogEntry( '', 'foo' );
	}
}
