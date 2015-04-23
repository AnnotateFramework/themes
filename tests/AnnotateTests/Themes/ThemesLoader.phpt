<?php

namespace AnnotateTests\Themes;

use Annotate\Themes\Loaders\ThemesLoader;
use Annotate\Themes\Theme;
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
		$this->themesLoader = new ThemesLoader(ROOT_DIR . DIRECTORY_SEPARATOR . 'Themes' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'themes', ROOT_DIR);
		$this->flattyTheme = new Theme(
			[
				'name' => 'Flatty',
				'version' => 0.1,
				'author' => 'Michal Vyšinský',
				'dependencies' => [
					'TwitterBootstrap' => [
						'version' => 3,
					],
				],
			], ROOT_DIR . DIRECTORY_SEPARATOR . 'Themes' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . 'Flatty' . DIRECTORY_SEPARATOR,
			DIRECTORY_SEPARATOR . 'Themes' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . 'Flatty' . DIRECTORY_SEPARATOR
		);
	}



	public function testItListensCorrectEvents()
	{
		$events = [
			'Annotate\Templating\TemplateFactory::onSetupTemplate',
			'Annotate\Templating\TemplateFactory::onLoadTemplate',
			'Annotate\Templating\TemplateFactory::onLoadLayout',
			'Annotate\Templating\TemplateFactory::onCreateFormTemplate' => 'onLoadComponentTemplate',
			'Annotate\Templating\TemplateFactory::onLoadComponentTemplate',
		];
		Assert::equal($events, $this->themesLoader->getSubscribedEvents());
	}



	public function testItLoadsTheme()
	{
		$this->themesLoader->activateTheme('Flatty');
		Assert::equal($this->flattyTheme, $this->themesLoader->getActiveTheme());
	}



	public function testItThrowsExceptionOnUnknownTheme()
	{
		Assert::exception(
			function () {
				$this->themesLoader->activateTheme('Unknown');
			},
			'Annotate\\Themes\\Exceptions\\ThemeNotFoundException'
		);
	}



	public function testItAddsPropertiesToTemplate()
	{
		$template = $this->createTemplate();
		$template->basePath = '/fake/base/path';
		$this->themesLoader->activateTheme('Flatty');
		$this->themesLoader->onSetupTemplate($template);

		Assert::true(isset($template->theme));
		Assert::true(isset($template->themeDir));
	}



	public function testItAddsLayouts()
	{
		$this->themesLoader->activateTheme('Flatty');
		$templateFactory = $this->mockista->create('Annotate\\Templating\\ITemplateFactory');
		$templateFactory->expects('addLayout')->twice();
		$this->themesLoader->onLoadLayout($templateFactory, '@layout.latte', 'TestPresenter');
	}



	public function testItDoesNothingWhenNoThemeIsSet()
	{
		$template = $this->createTemplate();
		$this->themesLoader->onSetupTemplate($template);

		Assert::false(isset($template->theme));
		Assert::false(isset($template->themeDir));

		$templateFactory = $this->mockista->create('\Annotate\Templating\ITemplateFactory');
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
		$this->themesLoader->activateTheme('Flatty');
		$template = $this->createTemplate();
		$this->themesLoader->onLoadComponentTemplate($template, 'mainPanel.latte');

		Assert::equal(
			ROOT_DIR . DIRECTORY_SEPARATOR . 'Themes' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . 'Flatty' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'mainPanel.latte',
			$template->getFile()
		);
	}



	/**
	 * @return Template
	 */
	private function createTemplate()
	{
		return new Template(new Engine);
	}

}


\run(new ThemesLoaderTest);
