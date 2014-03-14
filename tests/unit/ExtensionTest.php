<?php
use Codeception\Util\Stub;

class ExtensionTest extends \Codeception\TestCase\Test
{
   /**
    * @var \CodeGuy
    */
    protected $codeGuy;

    protected function createContainer()
    {
        $config = new \Nette\Configurator();
        $config->setTempDirectory(TEMP_DIR);
        $config->addConfig(DATA_DIR . '/config.neon');

        return $config->createContainer();
    }

    public function testExtensionAddsServices()
    {
        $dic = $this->createContainer();
        $this->assertTrue($dic->getService('themes.themeLoader') instanceof \AnnotateCms\Themes\Loaders\ThemesLoader);
    }

}