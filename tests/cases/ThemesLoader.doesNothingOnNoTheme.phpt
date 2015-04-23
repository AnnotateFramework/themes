<?php

require __DIR__ . '/../bootstrap.php';

$prophet = new Prophecy\Prophet();
$templateFactory = $prophet->prophesize(Annotate\Templating\ITemplateFactory::class);
$themesLoader = new Annotate\Themes\Loaders\ThemesLoader(__DIR__ . '/../data/themes', __DIR__ . '/..');
$latte = new Latte\Engine();
$template = new Nette\Bridges\ApplicationLatte\Template($latte);
$themesLoader->onSetupTemplate($template);
Tester\Assert::false(isset($template->theme));
Tester\Assert::false(isset($template->themeDir));
$themesLoader->onLoadTemplate($templateFactory->reveal(), 'template.latte', 'TestPresenter');
$themesLoader->onLoadLayout($templateFactory->reveal(), '@layout.latte', 'TestPresenter');
$themesLoader->onLoadComponentTemplate($template, 'mainPanel.latte');
Tester\Assert::true(empty($template->getFile()));
$templateFactory->addLayout()->shouldNotHaveBeenCalled();
$templateFactory->addTemplate()->shouldNotHaveBeenCalled();
$prophet->checkPredictions();
