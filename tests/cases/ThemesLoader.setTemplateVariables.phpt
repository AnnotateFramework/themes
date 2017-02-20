<?php

require __DIR__ . '/../bootstrap.php';

$themesLoader = new Annotate\Themes\Loaders\ThemesLoader(__DIR__ . '/../data/themes', __DIR__ . '/..');

$latte = new Latte\Engine();
$template = new Nette\Bridges\ApplicationLatte\Template($latte);
$template->basePath = '/fake/base/path';
$themesLoader->activateTheme('foo');
$themesLoader->onSetupTemplate($template);
Tester\Assert::true(isset($template->theme));
Tester\Assert::true(isset($template->themeDir));
