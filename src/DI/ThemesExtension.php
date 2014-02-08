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

    private $defaults = array(
        "frontend" => "Sandbox",
    );

    public function loadConfiguration()
    {
        $builder = $this->getContainerBuilder();

        $config = $this->getConfig($this->defaults);

        $builder->addDefinition($this->prefix("themesLoader"))
            ->setClass(ThemesLoader::classname)
            ->addTag(EventsExtension::SUBSCRIBER_TAG)
            ->addSetup("setFrontendTheme", array("name" => $config["frontend"]))
            ->addSetup("setBackendTheme", array("name" => $config["backend"]));
    }


} 