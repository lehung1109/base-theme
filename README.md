<p align="center">
  <a href="https://gulpjs.com">
    <img height="257" width="114" src="https://raw.githubusercontent.com/gulpjs/artwork/master/gulp-2x.png">
  </a>
  <p align="center">The streaming build system</p>
</p>

[![NPM version][npm-image]][npm-url] [![NODE][node-image]][node-url] [![GULP][gulp-image]][gulp-url]

# Sentius Template

## Getting started

### Prerequisites:
The sentius Styleguide has a few dependencies that you will need to install to use it. Don't worry, we're got all the link you need right here:

* [Node.js](https://nodejs.org/en/) – The backbone of the framework. Node Package Manager takes care of installing all the dev dependencies,
* [Gulp](http://gulpjs.com/) – Task manager and automation. Gulp is responsible for running all the small tasks like compiling SCSS and Twig templates, starting a web server and automatically reloading browsers.

### Starting a new project
So you got the prerequisites installed, and you're ready to get started? Great! Just follow the simple steps below:


1. Download a copy of the framework.

2. Open up the project folder in Terminal, navigate to the `.npm` folder.

    ```
    cd assets/.npm/
    ```

3. Run below command to install all the required packages with Node Package Manager.

    ```
    npm install
    ```

4. Start up watches and local server, run below command in `.npm` folder.

    ```
    gulp
    ```

[npm-url]: https://www.npmjs.com/package/gulp
[npm-image]: https://img.shields.io/npm/v/gulp.svg

[node-url]: https://github.com/nodejs/node
[node-image]: https://img.shields.io/badge/nodejs-v17.1.0-brightgreen

[gulp-url]: https://github.com/gulpjs/gulp
[gulp-image]: https://img.shields.io/badge/gulp-v4.0.2-brightgreen