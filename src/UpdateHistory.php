<?php

namespace Wikimedia\UpdateHistory;

/**
 * Update the HISTORY.md file just before/after a release.
 * Run this with `composer update-history`.
 */
class UpdateHistory {
	/**
	 * Main entry point.
	 * @param string $root Package root (where the HISTORY.md file is found)
	 * @param string $which One of 'patch', 'minor', or 'major'.
	 * @return int Exit code: zero on success, non-zero on failure.
	 */
	public static function main( string $root, string $which = 'patch' ): int {
		$changeLogPath = "$root/HISTORY.md";
		$changeLog = file_get_contents( $changeLogPath );
		$changeLog = preg_replace_callback(
			'/^(#+)( \S+)? (x\.x\.x|\d+\.\d+\.\d+)(.*)$/m',
			static function ( $matches ) use ( $changeLog, $which ) {
				$line = $matches[1] . ( $matches[2] ?? '' );
				if ( $matches[3] === 'x.x.x' ) {
					// Find the previous version
					if ( preg_match(
						'/^#+' . preg_quote( $matches[2] ?? '', '/' ) .
						' (\d+)\.(\d+)\.(\d+)/m', $changeLog, $m2
					) !== 1 ) {
						throw new \Exception( "Last version not found!" );
					}
					// Do a release!
					list( $ignore,$major,$minor,$patch ) = $m2;
					switch ( $which ) {
					case 'patch':
					case 'minor':
					case 'major':
						$$which = intval( $$which ) + 1;
						break;
					default:
						throw new \Exception( "Unknown version bump type: $which" );
					}
					$nextVersion = "$major.$minor.$patch";
					$date = date( 'Y-m-d' );
					return "$line $nextVersion ($date)";
				} else {
					// Bump after a release
					return "$line x.x.x (not yet released)\n\n" . $matches[0];
				}
			},
			$changeLog, 1, $count );
		if ( $count != 1 ) {
			throw new \Exception( "Changelog entry not found!" );
		}
		file_put_contents( $changeLogPath, $changeLog );
		return 0;
	}
}
