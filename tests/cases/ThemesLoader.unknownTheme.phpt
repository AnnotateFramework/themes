<?php

require __DIR__ . '/../bootstrap.php';

$themesLoader = new Annotate\Themes\Loaders\ThemesLoader(__DIR__ . '/../data/themes', __DIR__ . '/..');
Tester\Assert::exception(function () use ($themesLoader) {
	$themesLoader->activateTheme('unknown');
}, Annotate\Themes\Exceptions\ThemeNotFoundException::class);
