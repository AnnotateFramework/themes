<?php

require __DIR__ . '/../bootstrap.php';

$themesLoader = new Annotate\Themes\Loaders\ThemesLoader(__DIR__ . '/../data/themes', __DIR__ . '/..');
$template = new Nette\Bridges\ApplicationLatte\Template(new Latte\Engine());
$prophet = new Prophecy\Prophet();
$templateFactory = $prophet->prophesize(Annotate\Templating\ITemplateFactory::class);
$themesLoader->activateTheme('foo');
$themesLoader->onLoadLayout($templateFactory->reveal(), '@layout', 'TestPresenter');
$themesLoader->onLoadComponentTemplate($template, 'mainPanel.latte');
Tester\Assert::equal(__DIR__ . '/../data/themes/foo/templates/components/mainPanel.latte', $template->getFile());
$templateFactory->addLayout(Prophecy\Argument::type('string'))->shouldHaveBeenCalledTimes(2);
$prophet->checkPredictions();
