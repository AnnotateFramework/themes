<?php

use AnnotateCms\Themes\Theme;

class ThemeTest extends \Codeception\TestCase\Test
{
    /**
     * @var \CodeGuy
     */
    protected $codeGuy;

    /** @var  Theme */
    private $theme;


    protected function _before()
    {
        $this->theme = new \AnnotateCms\Themes\Theme(
            [
                'name' => 'Flatty',
                'version' => 0.1,
                'author' => 'Michal Vyšinský',
                'scripts' => [
                    '@js/flatty.js',
                ],
                'styles' => [
                    '@css/flatty.css',
                ],
                'dependencies' => [
                    'TwitterBootstrap' => [
                        'version' => '3',
                    ],
                ],
            ], '/home/michal/www/cms/vendor/annotatecms/themes/tests/_data/themes/Flatty/'
        );
    }


    public function testThemeReturnsItsName()
    {
        $this->assertSame('Flatty', $this->theme->getName());
    }


    public function testThemeReturnsItsVersion()
    {
        $this->assertEquals(0.1, $this->theme->getVersion());
    }


    public function testThemeReturnsItsAuthor()
    {
        $this->assertSame('Michal Vyšinský', $this->theme->getAuthor());
    }


    public function testThemeReturnsItsScripts()
    {
        $this->assertSame(
            [
                '@js/flatty.js',
            ],
            $this->theme->getScripts()
        );
    }


    public function testThemeReturnsItsStyles()
    {
        $this->assertSame(
            [
                '@css/flatty.css',
            ],
            $this->theme->getStyles()
        );
    }


    public function testThemeReturnsItsDependencies()
    {
        $this->assertSame(
            [
                'TwitterBootstrap' => [
                    'version' => '3'
                ]
            ],
            $this->theme->getDependencies()
        );

        $this->assertTrue($this->theme->hasDependencies());
    }


    public function testThemeReturnsItsPath()
    {
        $this->assertSame(
            '/home/michal/www/cms/vendor/annotatecms/themes/tests/_data/themes/Flatty/',
            $this->theme->getPath()
        );
    }


    public function testThemeReturnsItsRelativePath()
    {
        $this->assertSame(
            '_data/themes/Flatty/',
            $this->theme->getRelativePath()
        );
    }


    public function testCheckWorks()
    {
        $this->assertFalse($this->theme->isChecked());
        $this->theme->setChecked();
        $this->assertTrue($this->theme->isChecked());
    }

}