<?php
/**
 * Created by PhpStorm.
 * User: Michal
 * Date: 8.1.14
 * Time: 19:02
 */

namespace AnnotateCms\Themes\DI;


use Nette\DI\CompilerExtension;
use AnnotateCms\Themes\Loaders\ThemesLoader;

class ThemesExtension extends CompilerExtension
{

    private $defaults = array(
        "themesDir" => "%appDir%/themes"
    );

    public function loadConfiguration()
    {
        $config = $this->getConfig($this->defaults);

        dump($config);

        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix("themesLoader"))
            ->setClass(ThemesLoader::CLASS);
    }


} 