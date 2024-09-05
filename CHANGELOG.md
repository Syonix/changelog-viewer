# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [v1.0.0](https://github.com/Syonix/changelog-viewer/releases/tag/v1.0.0) - 2024-09-05
[See full Changelog](https://github.com/Syonix/changelog-viewer/compare/v0.1.8...v1.0.0)

### Updated
- PHP 8.1 compatibility (Thanks @fizdalf)
- Remove polyfill.io dependency (Thanks @fizdalf)

## [v0.1.8](https://github.com/Syonix/changelog-viewer/releases/tag/v0.1.8) - 2018-10-29
[See full Changelog](https://github.com/Syonix/changelog-viewer/compare/v0.1.7...v0.1.8)

### Fixed
- `HtmlFormatter`: Fixed huge font sizes

### Updated
- Added demo section to README

## [v0.1.7](https://github.com/Syonix/changelog-viewer/releases/tag/v0.1.7) - 2016-04-23
[See full Changelog](https://github.com/Syonix/changelog-viewer/compare/v0.1.6...v0.1.7)

### Fixed
- Fixed errors due to missing date in version.

## [v0.1.6](https://github.com/Syonix/changelog-viewer/releases/tag/v0.1.6) - 2016-04-23
[See full Changelog](https://github.com/Syonix/changelog-viewer/compare/v0.1.5...v0.1.6)

### Fixed
- Fixed incorrect regex for preceeding release.

## [v0.1.5](https://github.com/Syonix/changelog-viewer/releases/tag/v0.1.5) - 2016-04-23
[See full Changelog](https://github.com/Syonix/changelog-viewer/compare/v0.1.4...v0.1.5)

### Improved
- Changed default value for `printScripts` in `HtmlFormatter`
- Improved version regex, so it includes version numbers without link and unreleased tags.

## [v0.1.4](https://github.com/Syonix/changelog-viewer/releases/tag/v0.1.4) - 2016-02-02
[See full Changelog](https://github.com/Syonix/changelog-viewer/compare/v0.1.3...v0.1.4)

### Improved
- Updated German localisation

## [v0.1.3](https://github.com/Syonix/changelog-viewer/releases/tag/v0.1.3) - 2016-01-25
[See full Changelog](https://github.com/Syonix/changelog-viewer/compare/v0.1.2...v0.1.3)

### New
- Now supporting label naming scheme of [Keep A Changelog](http://keepachangelog.com/)

### Improved
- Now supporting unknown labels. They are displayed grey.
- Added colors for `Deprecated`, `Removed`, `Security`
- Updated colors for all labels


## [v0.1.2](https://github.com/Syonix/changelog-viewer/releases/tag/v0.1.2) - 2016-01-25
[See full Changelog](https://github.com/Syonix/changelog-viewer/compare/v0.1.1...v0.1.2)

### New
- Added support for unreleased versions #2
- Added support for yanked releases #4

### Improved
- Made release link and date optional #5

## [v0.1.1](https://github.com/Syonix/changelog-viewer/releases/tag/v0.1.1) - 2016-01-23
[See full Changelog](https://github.com/Syonix/changelog-viewer/compare/v0.1.0...v0.1.1)

### Fixed
- There was an error when instantiating `MarkdownProcessor`

## [v0.1.0](https://github.com/Syonix/changelog-viewer/releases/tag/v0.1.0) - 2016-01-23

### New
- Initial release
- Modular concept to support different sources and outputs
