[![Build Status](https://travis-ci.org/AnnotateFramework/themes.svg)](https://travis-ci.org/AnnotateFramework/themes)

Themes package for Annotate Framework
=====================================

This package provides powerfull themes support. You can create theme and override every template in theme.

Installation
------------

Note: this works when annotate/sandbox is installed

Run:

    composer require annotate/themes:@dev

Configure
---------

Default themes are set to `Sandbox` for frontend and `Flatty` for backend. Both are provided in sandbox package.
Themes path is also configurable via Neon. Default `directory` value is `%appDir%/app/addons/themes/`.
To change themes open app/config/app.neon and add following configuration:

    themes:
        directory: %appDir%/app/
        frontend: FrontendThemeName
        backend: BackendThemeName
    
Now edit any of your presenters:

    class FrontendPresenter extends BasePresenter
    {
        /** @var Annotate\Themes\Loaders\ThemesLoader @inject */
        public $themesLoader;
    
        public function startup()
        {
            parent::startup();
            $this->themesLoader->activateFrontendTheme(); // or activateBackendTheme()
        }
    }
    
Hint!
-----

For the best possible experience use annotate/packages package with annotate/themes
    
Uninstall
---------

Just remove line with `annotate/themes` from composer.json and run `composer update`

Remove `themes` section from `app/config/app.neon` file
Remove injection from your presenters and code added on installation.
