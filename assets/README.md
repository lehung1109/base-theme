<p align="center">
  <a href="https://gulpjs.com">
    <img height="257" width="114" src="https://raw.githubusercontent.com/gulpjs/artwork/master/gulp-2x.png">
  </a>
  <p align="center">The streaming build system</p>
</p>

[![NPM version][npm-image]][npm-url] [![NODE][node-image]][node-url]

## What is gulp?

- **Automation** - gulp is a toolkit that helps you automate painful or time-consuming tasks in your development workflow.
- **Platform-agnostic** - Integrations are built into all major IDEs and people are using gulp with PHP, .NET, Node.js, Java, and other platforms.
- **Strong Ecosystem** - Use npm modules to do anything you want + over 3000 curated plugins for streaming file transformations.
- **Simple** - By providing only a minimal API surface, gulp is easy to learn and simple to use.
- 
## What's new in 4.0?!

* The task system was rewritten from the ground-up, allowing task composition using `series()` and `parallel()` methods.
* The watcher was updated, now using chokidar (no more need for gulp-watch!), with feature parity to our task system.
* First-class support was added for incremental builds using `lastRun()`.
* A `symlink()` method was exposed to create symlinks instead of copying files.
* Built-in support for sourcemaps was added - the gulp-sourcemaps plugin is no longer necessary!
* Task registration of exported functions - using node or ES exports - is now recommended.
* Custom registries were designed, allowing for shared tasks or augmented functionality.
* Stream implementations were improved, allowing for better conditional and phased builds.

## Folder Structure

    .
    ├── .npm                   # config files
    ├── css                    # css file (don't modifiers)
    ├── js                     # js files
    ├── scss                   # scss files
    └── README.md
## Quick Start
Clone this repo and run the content locally
```bash
$ npm install
$ gulp
```

[npm-url]: https://www.npmjs.com/package/gulp
[npm-image]: https://img.shields.io/npm/v/gulp.svg

[node-url]: https://github.com/nodejs/node
[node-image]: https://img.shields.io/badge/nodejs-v17.1.0-brightgreen