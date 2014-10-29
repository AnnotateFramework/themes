<?php

namespace AnnotateCmsTests\Themes;

use AnnotateCms\Themes\Loaders\ThemesLoader;
use Nette;
use Tester;
use Tester\Assert;


require_once __DIR__ . '/../bootstrap.php';


class ExtensionTest extends Tester\TestCase
{

	public function setUp()
	{

	}


	public function testFunctional()
	{
		$container = $this->createContainer();
		Assert::true($container->getService('themes.themeLoader') instanceof ThemesLoader);
	}


	private function createContainer()
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);
		$config->addConfig(__DIR__ . '/data/config.neon');

		return $config->createContainer();
	}

}


\run(new ExtensionTest);
