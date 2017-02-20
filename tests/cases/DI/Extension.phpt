<?php

require __DIR__ . '/../../bootstrap.php';

$configurator = new Nette\Configurator();
$configurator->setTempDirectory(__DIR__ . '/../../temp');
$configurator->addConfig(__DIR__ . '/../../data/config.neon');
$container = $configurator->createContainer();
Tester\Assert::type(Annotate\Themes\Loaders\ThemesLoader::class, $container->getByType(Annotate\Themes\Loaders\ThemesLoader::class));
