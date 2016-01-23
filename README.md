# Changelog Viewer
[![Build Status](https://travis-ci.org/Syonix/changelog-viewer.svg?branch=master)](https://travis-ci.org/Syonix/changelog-viewer)
[![Total Downloads](https://poser.pugx.org/syonix/changelog-viewer/downloads.png)](https://packagist.org/packages/syonix/changelog-viewer)
[![Latest Stable Version](https://poser.pugx.org/syonix/changelog-viewer/v/stable.png)](https://packagist.org/packages/syonix/changelog-viewer)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/f0f52ed2-925c-4418-ba88-89c281bf4d44/mini.png)](https://insight.sensiolabs.com/projects/f0f52ed2-925c-4418-ba88-89c281bf4d44)

This package offers a nice way to display change logs.

Screenshot here...

Thanks a lot to [Grav CMS](http://getgrav.org) who let me use their design for the changelog modal. I use Grav myself and already replaced several Wordpress pages with it. Go check it out!

## Installation
### Using [Composer](https://getcomposer.org)
Call `composer require syonix/changelog-viewer`.

### Manual installation
Download the project files and upload them to your web server. 
Include the class autoloader `/vendor/autoload.php` or configure your own autoloader.

## Usage
To render a display, just call a factory function like this:
```php
use \Syonix\ChangelogViewer\Factory\ViewerFactory;
ViewerFactory::createMarkdownHtmlViewer(__DIR__ . '/../CHANGELOG.md')->build();
```

## Processors
Processors are the component of the library that reads the changelog file. Currently implemented is the `MarkdownProcessor` but you could add any of your own, as long as it implements the `Processor\Processor` Interface.

### MarkdownProcessor
The `MarkdownProcessor` takes the path to a markdown file and returns an `ArrayCollection` containing instances of `Version`.
The Markdown file must follow this scheme:
```md
# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).
```
This header is optional but follows the [Keep A Changelog](http://keepachangelog.com/) recommendation.

```md
## [v0.1.0](https://github.com/Syonix/changelog-viewer/releases/tag/v0.1.0) - 2016-01-23
[See full Changelog](https://github.com/Syonix/monolog-viewer/compare/v4.0.1...v4.0.2)
```
This is the version header. It contains the version string (adhering to [Semantic Versioning](http://semver.org/)) and a link where this release can be downloaded.

```md
### New
- Initial release
- Modular concept to support different sources and outputs
```

Next are the labels New, Changed and Fixed. `New` is for new features, `Changed` is for existing features that have been improved and `Fixed` is for bugs that have been fixed.
Under each label you have a list of changes that fall in this label (category).

This format can be overridden by setting a custom regex:
```php
$processor = new MarkdownProcessor($path)
    ->setRegex(array(
            'version' => '/^## \[(v.+)\]\((.+)\) - ([\d-]+)/',
            'changes_url' => '/^\[See full Changelog\]\((.+)\)/',
            'label' => '/^### (.+)/',
            'change' => '/^- (.+)/',
        ));
(new HtmlFormatter($processor))->build();
```

## Formatters
Formatters are used to display the change log. Currently there is only the `HtmlFormatter` which outputs the change log to HTML, but you could implement anything else.

### HtmlFormatter
The `HtmlFormatter` prints nice HTML. You have several options for that:
```php
(new HtmlFormatter($processor))
    ->title('Changelog')
    ->modal()
    ->frame(true)
    ->styles(true)
    ->scripts(true)
    ->downloadLinks(true)
    ->output();
```

Method | Default | Description
-------|---------|-----------------------------
`title(string)` | `Changelog` | Change the title of the modal.
`modal(bool)` | `false` | If set to true (or empty) the changelog will be displayed as a modal and hidden by default. User javascript functions `openChangelogModal()`, `closeChangelogModal()` and `toggleChangelogModal()` to manipulate. <br>**Note:** The default behavior when not calling this method is `false` but calling this method without a value will set it to `true`.
`frame(bool)` | `true` | If set to `false`, no frame will be printed.
`styles(bool)` | `true` | Print styles with the modal. Set to `false` if you want to include your own css.
`scripts(bool)` | `true` if `modal(true)` <br> `false` otherwise | Print the scripts used to open and close the modal. Set to `false` if you want to include your own js. <br>**Note:** Calling `modal(true)` also sets `scripts` to `true`, so if you want to add your own scripts for the modal, make sure you call `scripts(false)` after `modal()`.
`downloadLinks(bool)` | `false` | Display a download link for each version. Looks like this:<br> ![Build Status](https://github.com/Syonix/monolog-viewer/raw/master/demo/download_button.png)

`build()` | | Compiles and returns the change log HTML code according to the options above.
`output()` | | Calls `build()` and echoes the HTML code. <br>Same as `echo (new HtmlFormatter($processor))->build();`

## Localization

If your changelog file is written in another language, you can use the `LabelTranslator` so the parser recognizes the labels. Refer to the German example for the structure:
```json
{
  "new": "neu",
  "improved": "verbessert",
  "fixed": "behoben",
  "See full Changelog": "Vollständigen Changelog anzeigen"
}
```
