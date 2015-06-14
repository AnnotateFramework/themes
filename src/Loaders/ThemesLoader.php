<?php

namespace Annotate\Themes\Loaders;

use Annotate\Diagnostics\CmsPanel;
use Annotate\Templating\ITemplateFactory;
use Annotate\Themes\Exceptions\ThemeNotFoundException;
use Annotate\Themes\Theme;
use Kdyby\Events\Subscriber;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Neon\Neon;
use Nette\Object;
use Nette\Utils\Finder;
use Tracy\Dumper;


class ThemesLoader extends Object implements Subscriber
{

	/** @var callable[] */
	public $onActivateTheme = [];

	/** @var Theme[] */
	private $themes = [];

	/** @var Theme */
	private $activeTheme;

	/** @var string */
	private $themesDir;

	/** @var string */
	private $rootDir;



	/**
	 * @param  string
	 * @param  string
	 */
	public function __construct($themesDir, $rootDir)
	{
		$this->themesDir = $themesDir;
		$this->rootDir = $rootDir;
		$this->themes = $this->load();
	}



	/**
	 * @return Theme[]
	 */
	private function load()
	{
		if (!is_dir($this->themesDir)) {
			return NULL;
		}

		/** @var Theme[] $themes */
		$themes = [];
		foreach (Finder::findFiles('*theme.neon')->from($this->themesDir) as $path => $file) {
			$neon = Neon::decode(file_get_contents($path));
			$aDir = dirname($path) . DIRECTORY_SEPARATOR;
			$rDir = str_replace($this->rootDir, NULL, $aDir);
			$theme = new Theme($neon, $aDir, $rDir);

			if (isset($neon['extends'])) {
				$theme->setParent($neon['extends']);
			}

			$themes[$neon['name']] = $theme;
		}

		/** @var Theme $theme */
		foreach ($themes as $name => $theme) {
			$parent = $theme->getParent();
			if ($parent) {
				$theme->addDependencies($themes[$parent]->getDependencies());
			}
		}

		return $themes;
	}



	/**
	 * @param  string
	 */
	public function setThemesDir($themesDir)
	{
		$this->themesDir = $themesDir;
		$this->themes = $this->load();
	}



	/**
	 * @param  string
	 */
	public function activateTheme($themeName)
	{
		$this->activeTheme = $this->getTheme($themeName);
		$this->onActivateTheme($this->activeTheme);
		$this->addDebugSection();
	}



	/**
	 * @param  string
	 * @return Theme
	 * @throws ThemeNotFoundException
	 */
	private function getTheme($name)
	{
		if (isset($this->themes[$name])) {
			return $this->themes[$name];
		}
		throw new ThemeNotFoundException('Theme "' . $name . '" not found');
	}



	private function addDebugSection()
	{
		$theme = [
			'name' => $this->activeTheme->getName(),
			'version' => $this->activeTheme->getVersion(),
			'dependencies' => $this->activeTheme->getDependencies(),
		];
		CmsPanel::$sections[] = function () use ($theme) {
			$html = '<h2>Loaded Theme:</h2>';
			$html .= '<div><table>';
			$html .= '<thead><tr><th>Name</th><th>Version</th><th>Deps</th></tr></thead>';
			$html .= '<tr><td>' . $theme['name'] . '</td><td>' . $theme['version'] . '</td><td>' . Dumper::toHtml(
					$theme['dependencies'],
					[Dumper::COLLAPSE => TRUE]
				) . '</td></tr>';
			$html .= '</table></div>';

			return $html;
		};
	}



	public function getSubscribedEvents()
	{
		return [
			'Annotate\Templating\TemplateFactory::onSetupTemplate',
			'Annotate\Templating\TemplateFactory::onLoadTemplate',
			'Annotate\Templating\TemplateFactory::onLoadLayout',
			'Annotate\Templating\TemplateFactory::onCreateFormTemplate' => 'onLoadComponentTemplate',
			'Annotate\Templating\TemplateFactory::onLoadComponentTemplate',
		];
	}



	/**
	 * Event handler method - should not be called manually
	 * @internal
	 * @param  Template
	 */
	public function onSetupTemplate(Template $template)
	{
		if (!$this->activeTheme) {
			return;
		}
		$template->theme = $this->activeTheme;
		$template->themeDir = $template->basePath . '/' . $this->activeTheme->getRelativePath();
	}



	/**
	 * Event handler method - should not be called manually
	 * @internal
	 * @param  ITemplateFactory
	 * @param  string
	 * @param  string
	 */
	public function onLoadTemplate(ITemplateFactory $templateFactory, $templateFile, $presenterName)
	{
		if (!$this->activeTheme) {
			return;
		}
		$templateFactory->addTemplate(
			$this->formatTemplateFilePath($this->activeTheme, $templateFile, $presenterName)
		);
		$templateFactory->addTemplate(
			$this->formatTemplateFilePath($this->activeTheme, $templateFile)
		);

		$parent = $this->activeTheme->getParent();
		if (!$parent) {
			return;
		}
		$parentTheme = $this->getTheme($parent);
		$templateFactory->addTemplate(
			$this->formatTemplateFilePath($parentTheme, $templateFile, $presenterName)
		);
		$templateFactory->addTemplate(
			$this->formatTemplateFilePath($parentTheme, $templateFile)
		);
	}



	/**
	 * @param  Theme
	 * @param  string
	 * @param  string|NULL
	 * @return string
	 */
	private function formatTemplateFilePath(Theme $theme, $templateFile, $presenterName = NULL)
	{
		$base = $theme->getPath() . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
		if ($presenterName) {
			return $base . $presenterName . DIRECTORY_SEPARATOR . $templateFile . '.latte';
		}
		return $base . $templateFile . '.latte';
	}



	/**
	 * Event handler method - should not be called manually
	 * @internal
	 * @param  ITemplateFactory
	 * @param  string
	 * @param  string
	 */
	public function onLoadLayout(ITemplateFactory $templateFactory, $layoutFile, $presenterName)
	{
		if (!$this->activeTheme) {
			return;
		}

		$templateFactory->addLayout(
			$this->formatTemplateFilePath($this->activeTheme, $layoutFile, $presenterName)
		);
		$templateFactory->addLayout(
			$this->formatTemplateFilePath($this->activeTheme, $layoutFile)
		);

		$parent = $this->activeTheme->getParent();
		if (!$parent) {
			return;
		}
		$parentTheme = $this->getTheme($parent);
		$templateFactory->addLayout(
			$this->formatTemplateFilePath($parentTheme, $layoutFile, $presenterName)
		);
		$templateFactory->addLayout(
			$this->formatTemplateFilePath($parentTheme, $layoutFile)
		);
	}



	/**
	 * Event handler method - should not be called manually
	 * @internal
	 * @param  Template
	 * @param  string
	 */
	public function onLoadComponentTemplate(Template $template, $fileName)
	{
		if (!$this->activeTheme) {
			return;
		}

		$path = $this->activeTheme->getPath() . 'templates' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . $fileName;
		if (file_exists($path)) {
			$template->setFile($path);
		} elseif ($parent = $this->activeTheme->getParent()) {
			$parentTheme = $this->getTheme($parent);
			$path = $parentTheme->getPath() . 'templates' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . $fileName;
			if (file_exists($path)) {
				$template->setFile($path);
			}
		}
	}



	/**
	 * @return Theme
	 */
	public function getActiveTheme()
	{
		return $this->activeTheme;
	}

}
