<?php

namespace AnnotateCms\Themes\Loaders;

use AnnotateCms\Diagnostics\CmsPanel;
use AnnotateCms\Templating\ITemplateFactory;
use AnnotateCms\Themes\Exceptions\ThemeNotFoundException;
use AnnotateCms\Themes\Theme;
use Exception;
use Kdyby\Events\Subscriber;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Neon\Neon;
use Nette\Object;
use Nette\Utils\Finder;
use Tracy\Dumper;


/**
 * @method onActivateTheme(Theme $activeTheme)
 */
class ThemesLoader extends Object implements Subscriber
{

	const CLASSNAME = __CLASS__;

	public $onActivateTheme = [];

	private $themes = [];

	private $frontendTheme;

	private $backendTheme;

	/** @var  Theme */
	private $activeTheme;

	/** @var string */
	private $themesDir;

	/** @var string */
	private $rootDir;


	public function __construct($themesDir, $rootDir)
	{
		$this->themesDir = $themesDir;
		$this->rootDir = $rootDir;
		$this->themes = $this->load();
	}


	private function load()
	{
		if (!is_dir($this->themesDir)) {
			throw new Exception('Themes directory "' . $this->themesDir . '" not found.');
		}

		$themes = [];
		foreach (Finder::findFiles("*theme.neon")->from($this->themesDir) as $path => $file) {
			$neon = Neon::decode(\file_get_contents($path));
			$aDir = dirname($path) . DIRECTORY_SEPARATOR;
			$rDir = str_replace($this->rootDir, NULL, $aDir);
			$themes[$neon["name"]] = new Theme($neon, $aDir, $rDir);

		}

		return $themes;
	}


	public function setFrontendTheme($name)
	{
		$this->frontendTheme = $name;
	}


	public function setBackendTheme($name)
	{
		$this->backendTheme = $name;
	}


	public function activateFrontendTheme()
	{
		$this->activeTheme = $this->getTheme($this->frontendTheme);
		$this->onActivateTheme($this->activeTheme);
		$this->addDebugSection();
	}


	private function getTheme($name)
	{
		if (isset($this->themes[$name])) {
			return $this->themes[$name];
		}
		throw new ThemeNotFoundException("Theme '$name' not found");
	}


	private function addDebugSection()
	{
		$theme = [
			"name" => $this->activeTheme->getName(),
			"version" => $this->activeTheme->getVersion(),
			"dependencies" => $this->activeTheme->getDependencies(),
		];
		CmsPanel::$sections[] = function () use ($theme) {
			$html = "<h2>Loaded Theme:</h2>";
			$html .= "<div><table>";
			$html .= "<thead><tr><th>Name</th><th>Version</th><th>Deps</th></tr></thead>";
			$html .= "<tr><td>" . $theme["name"] . "</td><td>" . $theme["version"] . "</td><td>" . Dumper::toHtml(
					$theme["dependencies"],
					[Dumper::COLLAPSE => TRUE]
				) . "</td></tr>";
			$html .= "</table></div>";

			return $html;
		};
	}


	public function activateBackendTheme()
	{
		$this->activeTheme = $this->getTheme($this->backendTheme);
		$this->onActivateTheme($this->activeTheme);
		$this->addDebugSection();
	}


	public function getSubscribedEvents()
	{
		return [
			'AnnotateCms\Templating\TemplateFactory::onSetupTemplate',
			'AnnotateCms\Templating\TemplateFactory::onLoadTemplate',
			'AnnotateCms\Templating\TemplateFactory::onLoadLayout',
			'AnnotateCms\Templating\TemplateFactory::onCreateFormTemplate',
			'AnnotateCms\Templating\TemplateFactory::onLoadComponentTemplate',
		];
	}


	public function onSetupTemplate(Template $template)
	{
		if (!$this->activeTheme) {
			return;
		}
		$template->theme = $this->activeTheme;
		$template->themeDir = $template->basePath . "/" . $this->activeTheme->getRelativePath();
	}


	public function onLoadTemplate(ITemplateFactory $templateFactory, $templateFile, $presenterName)
	{

		if (!$this->activeTheme) {
			return;
		}

		$templateFactory->addTemplate(
			$this->formatTemplateFilePath($templateFile, $presenterName)
		);
		$templateFactory->addTemplate(
			$this->formatTemplateFilePath($templateFile)
		);
	}


	private function formatTemplateFilePath($templateFile, $presenterName = NULL)
	{
		$base = $this->activeTheme->getPath() . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR;
		if ($presenterName) {
			return $base . $presenterName . DIRECTORY_SEPARATOR . $templateFile . ".latte";
		} else {
			return $base . $templateFile . ".latte";
		}
	}


	public function onLoadLayout(ITemplateFactory $templateFactory, $layoutFile, $presenterName)
	{
		if (!$this->activeTheme) {
			return;
		}

		$templateFactory->addLayout(
			$this->formatTemplateFilePath($layoutFile, $presenterName)
		);
		$templateFactory->addLayout(
			$this->formatTemplateFilePath($layoutFile)
		);
	}


	public function onLoadComponentTemplate(Template $template, $fileName)
	{
		$this->onCreateFormTemplate($fileName, $template);
	}


	/**
	 * @param           $fileName
	 * @param Template  $template
	 *
	 * TODO: Remove and use only onLoadComponentTemplate method
	 */
	public function onCreateFormTemplate($fileName, Template $template)
	{
		if (!$this->activeTheme) {
			return;
		}

		$path = $this->activeTheme->getPath() . "templates" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . $fileName;
		if (file_exists($path)) {
			$template->setFile($path);
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
