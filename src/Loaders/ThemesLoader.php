<?php
/**
 * Created by PhpStorm.
 * User: Michal
 * Date: 8.1.14
 * Time: 20:11
 */

namespace AnnotateCms\Themes\Loaders;

use AnnotateCms\Packages\Exceptions\BadPackageVersionException;
use AnnotateCms\Packages\Exceptions\PackageNotFoundException;
use AnnotateCms\Packages\Loaders\PackageLoader;
use AnnotateCms\Themes\Theme;
use Kdyby\Events\Subscriber;
use Nette\Templating\IFileTemplate;
use Nette\Templating\ITemplate;
use Nette\Utils\Finder;
use Nette\Utils\Neon;

if (!defined("THEMES_DIR")) {
    define("THEMES_DIR", APP_DIR . "themes" . DS);
}


/**
 * Class ThemesLoader
 * @package AnnotateCms\Themes\Loaders
 */
class ThemesLoader implements Subscriber
{

    private $themes = array();

    const classname = __CLASS__;
    /** @var  Theme */
    private $activeTheme;

    function __construct(PackageLoader $packageLoader)
    {
        $this->packageLoader = $packageLoader;
        $this->themes = $this->load();
    }

    private function load()
    {
        $themes = array();

        foreach (Finder::findFiles("*theme.neon")->from(\THEMES_DIR) as $path => $file) {
            $neon = Neon::decode(\file_get_contents($path));
            $aDir = \dirname($path);
            $themes[$neon["name"]] = new Theme($neon, $aDir);

        }

        return $themes;
    }

    private function checkDependencies(Theme $theme)
    {
        if ($theme->isChecked()) {
            return true;
        }

        if (!$theme->hasDependencies()) {
            return true;
        }

        foreach ($theme->getDependencies() as $name => $info) {
            $version = isset($info["version"]) ? $info["version"] : null;
            $variant = isset($info["variant"]) ? $info["variant"] : "default";

            try {
                $this->packageLoader->getPackage($name, $version, $variant);
            } catch (PackageNotFoundException $e) {
                throw new PackageNotFoundException("Theme cannot be loaded. Package '$name' does not exist.", 0, $e);
            } catch (BadPackageVersionException $e) {
                throw new BadPackageVersionException("Theme cannot be loaded. Theme requires '$name' version $version", 0, $e);
            }
        }
        $theme->setChecked();
        return true;
    }

    public function getSubscribedEvents()
    {
        return array(
            'AnnotateCms\Framework\Templating\TemplateFactory::onSetupTemplate'
        );
    }

    public function onSetupTemplate(ITemplate $template)
    {
        $template->theme = $this->activeTheme;
        $template->themeDir = $template->basePath . $this->activeTheme->getRelativeDirectory();
    }

} 