<?php

namespace Wikimedia\UpdateHistory;

use RuntimeException;

/**
 * Update the HISTORY.md file just before/after a release.
 * Run this with `composer update-history`.
 */
class UpdateHistory {
	/**
	 * Main entry point.
	 * @param string $root Package root (where the HISTORY.md file is found).
	 * @param string $which One of 'patch', 'minor', or 'major'.
	 * @return int Exit code: zero on success, non-zero on failure.
	 * @codeCoverageIgnore
	 */
	public static function main( string $root, string $which = 'patch' ): int {
		$changeLogPath = "$root/HISTORY.md";
		file_put_contents(
			$changeLogPath,
			self::addChangelogEntry( file_get_contents( $changeLogPath ), $which )
		);
		return 0;
	}

	/**
	 * @param string $changeLog Contents of the HISTORY.md file.
	 * @param string $which One of 'patch', 'minor', or 'major'.
	 *
	 * @return array|string|string[]|null
	 */
	public static function addChangelogEntry( string $changeLog, string $which = 'patch' ) {
		$changeLog = preg_replace_callback(
			'/^(#+)( \S+)? (x\.x\.x|\d+\.\d+\.\d+)(.*)$/m',
			static function ( $matches ) use ( $changeLog, $which ) {
				$line = $matches[1] . ( $matches[2] ?? '' );
				if ( $matches[3] !== 'x.x.x' ) {
					// Bump after a release
					return "$line x.x.x (not yet released)\n\n" . $matches[0];
				}

				// Find the previous version
				if (
					preg_match(
						'/^#+' . preg_quote( $matches[2] ?? '', '/' ) .
						' (\d+)\.(\d+)\.(\d+)/m', $changeLog, $m2
					) !== 1
				) {
					throw new RuntimeException( "Last version not found!" );
				}
				// Do a release!
				[ , $major, $minor, $patch ] = $m2;
				switch ( $which ) {
					case 'patch':
						$patch++;
						break;
					case 'minor':
						$minor++;
						break;
					case 'major':
						$major++;
						break;
					default:
						throw new RuntimeException( "Unknown version bump type: $which" );
				}
				$nextVersion = "$major.$minor.$patch";
				$date = date( 'Y-m-d' );
				return "$line $nextVersion ($date)";
			},
			$changeLog,
			1,
			$count
		);
		if ( $count !== 1 ) {
			throw new RuntimeException( "Changelog entry not found!" );
		}
		return $changeLog;
	}
}
