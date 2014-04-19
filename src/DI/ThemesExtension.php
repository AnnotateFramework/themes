<?php
/**
 * Created by PhpStorm.
 * User: Michal
 * Date: 8.1.14
 * Time: 19:02
 */

namespace AnnotateCms\Themes\DI;


use AnnotateCms\Themes\Loaders\ThemesLoader;
use Kdyby\Events\DI\EventsExtension;
use Nette\DI\CompilerExtension;

class ThemesExtension extends CompilerExtension
{
    public function loadConfiguration()
    {
        $configuration = $this->getConfig($this->getDefaults());

        $this->getContainerBuilder()->addDefinition($this->prefix('themeLoader'))
            ->setClass(ThemesLoader::classname)
            ->addTag(EventsExtension::SUBSCRIBER_TAG)
            ->addSetup('setFrontendTheme', ['name' => $configuration['frontend']])
            ->addSetup('setBackendTheme', ['name' => $configuration['backend']]);
    }


    function  getDefaults()
    {
        return [
            'frontend' => '',
            'backend' => '',
        ];
    }
}
