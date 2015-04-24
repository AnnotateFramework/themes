[![Travis](https://img.shields.io/travis/AnnotateFramework/themes.svg?style=flat-square)](https://travis-ci.org/AnnotateFramework/themes)
[![Code Climate](https://img.shields.io/codeclimate/github/AnnotateFramework/themes.svg?style=flat-square)](https://codeclimate.com/github/AnnotateFramework/themes)
[![Code Climate](https://img.shields.io/codeclimate/coverage/github/AnnotateFramework/themes.svg?style=flat-square)](https://codeclimate.com/github/AnnotateFramework/themes)


[![Packagist](https://img.shields.io/packagist/v/annotate/themes.svg?style=flat-square)](https://packagist.org/packages/annotate/themes)
[![Packagist](https://img.shields.io/packagist/dm/annotate/themes.svg?style=flat-square)](https://packagist.org/packages/annotate/themes)
[![Packagist](https://img.shields.io/packagist/dd/annotate/themes.svg?style=flat-square)](https://packagist.org/packages/annotate/themes)
[![Packagist](https://img.shields.io/packagist/dt/annotate/themes.svg?style=flat-square)](https://packagist.org/packages/annotate/themes)

Themes package for Annotate Framework
=====================================

This package provides powerfull themes support. You can create theme and override every template in theme.

Installation
------------

Require this extension by [Composer](http://getcomposer.org)

```sh
$ composer require annotate/themes:@dev
```

Register extension into configuration:

```yml
extensions:
    themes: Annotate\Themes\DI\ThemesExtension
```

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
