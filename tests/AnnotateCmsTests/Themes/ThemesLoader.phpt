<?php

namespace AnnotateCmsTests\Themes;

use AnnotateCms\Themes\Loaders\ThemesLoader;
use AnnotateCms\Themes\Theme;
use Latte\Engine;
use Nette\Bridges\ApplicationLatte\Template;
use Tester;
use Tester\Assert;


require_once __DIR__ . '/../bootstrap.php';



class ThemesLoaderTest extends TestCase
{

	/** @var ThemesLoader */
	private $themesLoader;


	private $flattyTheme;


	public function setUp()
	{
		parent::setUp();
		$this->themesLoader = new ThemesLoader(ROOT_DIR . '/Themes/data/themes');
		$this->flattyTheme = new Theme(
			[
				'name'         => 'Flatty',
				'version'      => 0.1,
				'author'       => 'Michal Vyšinský',
				'scripts'      => [
					'@js/flatty.js',
				],
				'styles'       => [
					'@css/flatty.css',
				],
				'dependencies' => [
					'TwitterBootstrap' => [
						'version' => 3,
					],
				],
			], ROOT_DIR . '/Themes/data/themes/Flatty/'
		);
	}


	public function testItListensCorrectEvents()
	{
		$events = [
			'AnnotateCms\Templating\TemplateFactory::onSetupTemplate',
			'AnnotateCms\Templating\TemplateFactory::onLoadTemplate',
			'AnnotateCms\Templating\TemplateFactory::onLoadLayout',
			'AnnotateCms\Templating\TemplateFactory::onCreateFormTemplate',
			'AnnotateCms\Templating\TemplateFactory::onLoadComponentTemplate',
		];
		Assert::equal($events, $this->themesLoader->getSubscribedEvents());
	}


	public function testItLoadsFrontendTheme()
	{
		$this->themesLoader->setFrontendTheme('Flatty');
		$this->themesLoader->activateFrontendTheme();
		Assert::equal($this->flattyTheme, $this->themesLoader->getActiveTheme());
	}


	public function testItLoadsBackendTheme()
	{
		$this->themesLoader->setBackendTheme('Flatty');
		$this->themesLoader->activateBackendTheme();
		Assert::equal($this->flattyTheme, $this->themesLoader->getActiveTheme());
	}


	public function testItThrowsExceptionOnUnknownTheme()
	{
		Assert::exception(
			function () {
				$this->themesLoader->setFrontendTheme('Unknown');
				$this->themesLoader->activateFrontendTheme();
			},
			'AnnotateCms\\Themes\\Exceptions\\ThemeNotFoundException'
		);
	}


	public function testItAddsPropertiesToTemplate()
	{
		$template = new Template(new Engine);
		$template->basePath = '/fake/base/path';
		$this->themesLoader->setFrontendTheme('Flatty');
		$this->themesLoader->activateFrontendTheme();
		$this->themesLoader->onSetupTemplate($template);

		Assert::true(isset($template->theme));
		Assert::true(isset($template->themeDir));
	}


	public function testItAddsLayouts()
	{
		$this->themesLoader->setFrontendTheme('Flatty');
		$this->themesLoader->activateFrontendTheme();
		$templateFactory = $this->mockista->create('AnnotateCms\\Templating\\ITemplateFactory');
		$templateFactory->expects('addLayout')->twice();
		$this->themesLoader->onLoadLayout($templateFactory, '@layout.latte', 'TestPresenter');
	}


	public function testItDoesNothingWhenNoThemeIsSet()
	{
		$template = new Template(new Engine);
		$this->themesLoader->onSetupTemplate($template);

		Assert::false(isset($template->theme));
		Assert::false(isset($template->themeDir));

		$templateFactory = $this->mockista->create('\AnnotateCms\Templating\ITemplateFactory');
		$templateFactory->expects('addTemplate')->exactly(0);
		$this->themesLoader->onLoadTemplate($templateFactory, 'template.latte', 'TestPresenter');

		$templateFactory->expects('addLayout')->exactly(0);
		$this->themesLoader->onLoadLayout($templateFactory, '@layout.latte', 'TestPresenter');

		$this->themesLoader->onLoadComponentTemplate($template, 'mainPanel.latte');
		$file = $template->getFile();

		Assert::true(empty($file));
	}


	public function testItLoadsComponentsTemplate()
	{
		$this->themesLoader->setFrontendTheme('Flatty');
		$this->themesLoader->activateFrontendTheme();
		$template = new Template(new Engine);
		$this->themesLoader->onLoadComponentTemplate($template, 'mainPanel.latte');

		Assert::equal(
			ROOT_DIR . '/Themes/data/themes/Flatty/templates/components/mainPanel.latte',
			$template->getFile()
		);
	}

}



\run(new ThemesLoaderTest);
