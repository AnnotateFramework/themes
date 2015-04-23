<?php

namespace Annotate\Themes\DI;

use Annotate\Themes\Loaders\ThemesLoader;
use Kdyby\Events\DI\EventsExtension;
use Nette\DI\CompilerExtension;


class ThemesExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$configuration = $this->getConfig($this->getDefaults());

		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('themeLoader'))
			->setClass(ThemesLoader::class, [
				'themesDir' => $configuration['directory'],
				'rootDir' => $builder->expand('%appDir%') . '/../',
			])
			->addTag(EventsExtension::TAG_SUBSCRIBER);
	}



	public function getDefaults()
	{
		return [
			'directory' => '%appDir%/addons/themes/',
		];
	}

}
