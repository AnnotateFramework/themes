Themes package for AnnotateCms
==============================

This package provides powerfull themes support. You can create theme and override every template in theme.

Installation
------------

Note: this works when annotatecms/sandbox is installed

Run:

  composer require annotatecms/themes:@dev

Configure
---------

Default themes are set to `Sandbox` for frontend and `Flatty` for backend. Both are provided in sandbox package. 
To change themes open app/config/app.neon and add following configuration:

  themes:
    frontend: FrontendThemeName
    backend: BackendThemeName
    
Now edit any of your presenters:

    class FrontendPresenter extends BasePresenter
    {
        /** @var AnnotateCms\Themes\Loaders\ThemesLoader @inject */
       public $themesLoader;
    
       public function startup()
       {
          parent::startup();
          $this->themesLoader->activateFrontendTheme(); // or activateBackendTheme()
       }
    }
    
Hint!
-----

For the best possible experience use annotatecms/packages package with annotatecms/themes
    
Uninstall
---------

Just remove line with `annotatecms/themes` from composer.json and run `composer update`

Remove `themes` section from `app/config/app.neon` file
