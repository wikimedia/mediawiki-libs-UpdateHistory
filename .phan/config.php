<?php

$cfg = require __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config.php';
$cfg['directory_list'] = [
	'src',
	'tests',
	'.phan/stubs',
];

// Ignored to allow upgrading Phan, to be fixed later.
$cfg['suppress_issue_types'][] = 'MediaWikiNoBaseException';

return $cfg;
