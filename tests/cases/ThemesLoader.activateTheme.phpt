<?php

require __DIR__ . '/../bootstrap.php';

$theme = new Annotate\Themes\Theme(
	[
		'name' => 'foo',
		'version' => 0.1,
		'author' => 'John Doe',
		'dependencies' => [
			'package' => [
				'version' => 2.0,
			],
		],
	],
	__DIR__ . '/../data/themes/foo/',
	'/data/themes/foo/'
);

$themesLoader = new Annotate\Themes\Loaders\ThemesLoader(__DIR__ . '/../data/themes', __DIR__ . '/..');
$themesLoader->activateTheme('foo');
Tester\Assert::equal($theme, $themesLoader->getActiveTheme());
