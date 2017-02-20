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
	__DIR__ . '/data/themes/foo',
	'/data/themes/foo'
);

Tester\Assert::same('foo', $theme->getName());
Tester\Assert::equal(0.1, $theme->getVersion());
Tester\Assert::same('John Doe', $theme->getAuthor());
Tester\Assert::same(
	[
		'package' => [
			'version' => 2.0
		],
	],
	$theme->getDependencies()
);
Tester\Assert::same(__DIR__ . '/data/themes/foo', $theme->getPath());
Tester\Assert::same('/data/themes/foo', $theme->getRelativePath());
Tester\Assert::false($theme->isChecked());
$theme->setChecked();
Tester\Assert::true($theme->isChecked());
