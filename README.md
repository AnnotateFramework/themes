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

Requirements
------------

Themes extension requires [annotate/templating](https://github.com/AnnotateFramework/templating). Register its extension to your neon config too.

Installation
------------

Require this extension by [Composer](http://getcomposer.org)

```sh
$ composer require annotate/themes:~2.1.0
```

Register extension into configuration:

```yml
extensions:
    templating: Annotate\Templating\DI\TemplatingExtension
    themes: Annotate\Themes\DI\ThemesExtension
```

Configure
---------

Themes path is configurable via Neon. Default `directory` value is `%appDir%/app/addons/themes/`.
To change themes open app/config/app.neon and add following configuration:

    themes:
        directory: %appDir%/app/
    
Now edit any of your presenters:

	class FrontendPresenter extends Nette\Application\UI\Presenter
	{
	
		use Annotate\Themes\ThemedPresenter;
	
		/** @var Annotate\Themes\Loaders\ThemesLoader @inject */
		public $themesLoader;
	
		public function startup()
		{
			parent::startup();
			$this->themesLoader->activateTheme('theme name');
		}
	}

Create theme
------------

Create a file `theme_name.theme.neon` in themes directory with minimal structure:


```neon
name: My theme
```

Inheritance
-----------

Themes support one level inheritance you can specify parent theme by `extends` option in neon file:

```neon
name: My theme
extends: theme
```

Loading templates
-----------------

After activating theme app will search for template files this way:
 
 1. search for `%themeDir%/templates/%templateName`
 2. in case theme extends another theme it seaches for `%anotherThemeDir%/templates/%templateName%`
 3. if no template was found above it searches for file in normal "Nette" way
