<?php

class ThemesLoaderTest extends \Codeception\TestCase\Test
{
    /**
     * @var \CodeGuy
     */
    protected $codeGuy;

    /** @var  \AnnotateCms\Themes\Loaders\ThemesLoader */
    private $themesLoader;


    protected function _before()
    {
        $this->themesLoader = new \AnnotateCms\Themes\Loaders\ThemesLoader(DATA_DIR . '/themes');
    }


    public function testItListensCorrectEvents()
    {
        $events = [
            'AnnotateCms\Framework\Templating\TemplateFactory::onSetupTemplate',
            'AnnotateCms\Framework\Templating\TemplateFactory::onLoadTemplate',
            'AnnotateCms\Framework\Templating\TemplateFactory::onLoadLayout',
            'AnnotateCms\Framework\Templating\TemplateFactory::onCreateFormTemplate',
            'AnnotateCms\Framework\Templating\TemplateFactory::onLoadComponentTemplate',
        ];
        $this->assertEquals($events, $this->themesLoader->getSubscribedEvents());
    }


    public function testItLoadsFrontendTheme()
    {
        $this->themesLoader->setFrontendTheme('Flatty');
        $this->themesLoader->activateFrontendTheme();

        $theme = new \AnnotateCms\Themes\Theme(
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
            ], DATA_DIR . '/themes/Flatty/'
        );

        $this->assertEquals($theme, $this->themesLoader->getActiveTheme());
    }


    public function testItLoadsBackendTheme()
    {
        $this->themesLoader->setBackendTheme('Flatty');
        $this->themesLoader->activateBackendTheme();
        $theme = new \AnnotateCms\Themes\Theme(
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
            ], DATA_DIR . '/themes/Flatty/'
        );
        $this->assertEquals($theme, $this->themesLoader->getActiveTheme());
    }


    /**
     * @expectedException \AnnotateCms\Themes\Exceptions\ThemeNotFoundException
     */
    public function testItThrowsExceptionOnUnknownTheme()
    {
        $this->themesLoader->setFrontendTheme('Unknown');
        $this->themesLoader->activateFrontendTheme();
    }


    public function testItAddsPropertiesToTemplate()
    {
        $template = new \Nette\Bridges\ApplicationLatte\Template(new \Latte\Engine());
        $template->basePath = '/fake/base/path';
        $this->themesLoader->setFrontendTheme('Flatty');
        $this->themesLoader->activateFrontendTheme();
        $this->themesLoader->onSetupTemplate($template);

        $this->assertNotEmpty($template->theme);
        $this->assertNotEmpty($template->themeDir);
    }


    public function testItAddsTemplates()
    {

        $this->themesLoader->setFrontendTheme('Flatty');
        $this->themesLoader->activateFrontendTheme();

        $templateFactory = $this->getMock('\AnnotateCms\Framework\Templating\ITemplateFactory');
        $templateFactory->expects($this->exactly(2))
            ->method('addTemplate');
        $this->themesLoader->onLoadTemplate($templateFactory, 'template.latte', 'TestPresenter');
    }


    public function testItAddsLayouts()
    {

        $this->themesLoader->setFrontendTheme('Flatty');
        $this->themesLoader->activateFrontendTheme();

        $templateFactory = $this->getMock('\AnnotateCms\Framework\Templating\ITemplateFactory');
        $templateFactory->expects($this->exactly(2))
            ->method('addLayout');
        $this->themesLoader->onLoadLayout($templateFactory, '@layout.latte', 'TestPresenter');
    }


    public function testItDoesNothingWhenNoThemeIsSet()
    {
        $template = new \Nette\Bridges\ApplicationLatte\Template(new \Latte\Engine());
        $this->themesLoader->onSetupTemplate($template);

        $this->assertFalse(isset($template->theme));
        $this->assertFalse(isset($template->themeDir));

        $templateFactory = $this->getMock('\AnnotateCms\Framework\Templating\ITemplateFactory');
        $templateFactory->expects($this->exactly(0))
            ->method('addTemplate');
        $this->themesLoader->onLoadTemplate($templateFactory, 'template.latte', 'TestPresenter');

        $templateFactory->expects($this->exactly(0))
            ->method('addLayout');
        $this->themesLoader->onLoadLayout($templateFactory, '@layout.latte', 'TestPresenter');

        $this->themesLoader->onLoadComponentTemplate($template, 'mainPanel.latte');

        $this->assertEmpty($template->getFile());
    }


    public function testItLoadsComponentsTemplate()
    {
        $this->themesLoader->setFrontendTheme('Flatty');
        $this->themesLoader->activateFrontendTheme();
        $template = new \Nette\Bridges\ApplicationLatte\Template(new \Latte\Engine());
        $this->themesLoader->onLoadComponentTemplate($template, 'mainPanel.latte');

        $this->assertEquals(
            DATA_DIR . '/themes/Flatty/templates/components/mainPanel.latte',
            $template->getFile()
        );
    }

}