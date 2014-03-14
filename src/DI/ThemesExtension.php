<?php
/**
 * Created by PhpStorm.
 * User: Michal
 * Date: 8.1.14
 * Time: 19:02
 */

namespace AnnotateCms\Themes\DI;


use AnnotateCms\Framework\DI\CompilerExtension;
use AnnotateCms\Themes\Loaders\ThemesLoader;
use Kdyby\Events\DI\EventsExtension;

class ThemesExtension extends CompilerExtension
{


    function getServices()
    {
        $config = $this->getConfig();
        return [
            "themeLoader" => [
                "class" => ThemesLoader::classname,
                "tags" => [EventsExtension::SUBSCRIBER_TAG],
                "setup" => [
                    "setFrontendTheme" => ["name" => $config["frontend"]],
                    "setBackendTheme" => ["name" => $config["backend"]],
                ],
            ],
        ];
    }


    function getFactories()
    {
        // TODO: Implement getFactories() method.
    }


    function  getDefaults()
    {
        return [
            "frontend" => "Sandbox",
            "backend" => "Flatty",
        ];
    }
}
