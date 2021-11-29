/**
 * Linting Sass stylesheets with Stylelint
 * http://www.creativenightly.com/2016/02/How-to-lint-your-css-with-stylelint/
 */

'use strict';

const gulp        = require('gulp');
const postcss     = require('gulp-postcss');
const reporter    = require('postcss-reporter');
const syntax_scss = require('postcss-scss');
const stylelint   = require('stylelint');
const cssnano = require('cssnano');
const sass = require('gulp-sass')(require('sass'));
const log = require('fancy-log');
const autoprefixer = require('autoprefixer');
const eslint = require('gulp-eslint-new');

gulp.task('build', function (done) {
  const stylelintConfig = {
    "rules": {
		'alpha-value-notation': [
			'percentage',
			{
				exceptProperties: ['opacity'],
			},
		],
		'at-rule-empty-line-before': [
			'always',
			{
				except: ['blockless-after-same-name-blockless', 'first-nested'],
				ignore: ['after-comment'],
			},
		],
		'at-rule-name-case': 'lower',
		'at-rule-name-space-after': 'always-single-line',
		'at-rule-no-vendor-prefix': true,
		'at-rule-semicolon-newline-after': 'always',
		'block-closing-brace-empty-line-before': 'never',
		'block-closing-brace-newline-after': 'always',
		'block-closing-brace-newline-before': 'always-multi-line',
		'block-closing-brace-space-before': 'always-single-line',
		'block-opening-brace-newline-after': 'always-multi-line',
		'block-opening-brace-space-after': 'always-single-line',
		'block-opening-brace-space-before': 'always',
		'color-hex-case': 'lower',
		'color-hex-length': 'short',
		'comment-empty-line-before': [
			'always',
			{
				except: ['first-nested'],
				ignore: ['stylelint-commands'],
			},
		],
		'comment-whitespace-inside': 'always',
		'custom-property-empty-line-before': [
			'always',
			{
				except: ['after-custom-property', 'first-nested'],
				ignore: ['after-comment', 'inside-single-line-block'],
			},
		],
		'custom-media-pattern': [
			'^([a-z][a-z0-9]*)(-[a-z0-9]+)*$',
			{
				message: 'Expected custom media query name to be kebab-case',
			},
		],
		'custom-property-pattern': [
			'^([a-z][a-z0-9]*)(-[a-z0-9]+)*$',
			{
				message: 'Expected custom property name to be kebab-case',
			},
		],
		'declaration-bang-space-after': 'never',
		'declaration-bang-space-before': 'always',
		'declaration-block-semicolon-newline-after': 'always-multi-line',
		'declaration-block-semicolon-space-after': 'always-single-line',
		'declaration-block-semicolon-space-before': 'never',
		'declaration-block-single-line-max-declarations': 1,
		'declaration-block-trailing-semicolon': 'always',
		'declaration-block-no-redundant-longhand-properties': true,
		'declaration-colon-newline-after': 'always-multi-line',
		'declaration-colon-space-after': 'always-single-line',
		'declaration-colon-space-before': 'never',
		'declaration-empty-line-before': [
			'always',
			{
				except: ['after-declaration', 'first-nested'],
				ignore: ['after-comment', 'inside-single-line-block'],
			},
		],
		'font-family-name-quotes': 'always-where-recommended',
		'function-comma-newline-after': 'always-multi-line',
		'function-comma-space-after': 'always-single-line',
		'function-comma-space-before': 'never',
		'function-max-empty-lines': 0,
		'function-name-case': 'lower',
		'function-parentheses-newline-inside': 'always-multi-line',
		'function-parentheses-space-inside': 'never-single-line',
		'function-url-quotes': 'always',
		'function-whitespace-after': 'always',
		'hue-degree-notation': 'angle',
		indentation: 2,
		'keyframes-name-pattern': [
			'^([a-z][a-z0-9]*)(-[a-z0-9]+)*$',
			{
				message: 'Expected keyframe name to be kebab-case',
			},
		],
		'length-zero-no-unit': true,
		'max-empty-lines': 1,
		'max-line-length': 120,
		'media-feature-colon-space-after': 'always',
		'media-feature-colon-space-before': 'never',
		'media-feature-name-case': 'lower',
		'media-feature-name-no-vendor-prefix': true,
		'media-feature-parentheses-space-inside': 'never',
		'media-feature-range-operator-space-after': 'always',
		'media-feature-range-operator-space-before': 'always',
		'media-query-list-comma-newline-after': 'always-multi-line',
		'media-query-list-comma-space-after': 'always-single-line',
		'media-query-list-comma-space-before': 'never',
		'no-empty-first-line': true,
		'no-eol-whitespace': true,
		'no-irregular-whitespace': true,
		'no-missing-end-of-source-newline': true,
		'number-leading-zero': 'always',
		'number-max-precision': 4,
		'number-no-trailing-zeros': true,
		'property-case': 'lower',
		'property-no-vendor-prefix': true,
		'rule-empty-line-before': [
			'always-multi-line',
			{
				except: ['first-nested'],
				ignore: ['after-comment'],
			},
		],
		'selector-attribute-brackets-space-inside': 'never',
		'selector-attribute-operator-space-after': 'never',
		'selector-attribute-operator-space-before': 'never',
		'selector-attribute-quotes': 'always',
		'selector-class-pattern': [
			'^([a-z][a-z0-9]*)(-[a-z0-9]+)*$',
			{
				message: 'Expected class selector to be kebab-case',
			},
		],
		'selector-combinator-space-after': 'always',
		'selector-combinator-space-before': 'always',
		'selector-descendant-combinator-no-non-space': true,
		'selector-id-pattern': [
			'^([a-z][a-z0-9]*)(-[a-z0-9]+)*$',
			{
				message: 'Expected id selector to be kebab-case',
			},
		],
		'selector-list-comma-newline-after': 'always',
		'selector-list-comma-space-before': 'never',
		'selector-max-empty-lines': 0,
		'selector-no-vendor-prefix': true,
		'selector-pseudo-class-case': 'lower',
		'selector-pseudo-class-parentheses-space-inside': 'never',
		'selector-pseudo-element-case': 'lower',
		'selector-pseudo-element-colon-notation': 'double',
		'selector-type-case': 'lower',
		'shorthand-property-no-redundant-values': true,
		'string-quotes': 'double',
		'unit-case': 'lower',
		'value-keyword-case': 'lower',
		'value-list-comma-newline-after': 'always-multi-line',
		'value-list-comma-space-after': 'always-single-line',
		'value-list-comma-space-before': 'never',
		'value-list-max-empty-lines': 0,
		'value-no-vendor-prefix': true,
    }
  };

  const processors = [
    stylelint(stylelintConfig),
    reporter({
      clearMessages: true,
      throwError: true
    }),
    require('autoprefixer'),
    require('postcss-nested')
  ];

  gulp.src(
	  ['../scss/**/*.scss']
  	)
    .pipe(postcss(processors, {syntax: syntax_scss}))
    .pipe(sass({outputStyle: 'compressed'}).on('error', function(err){
      log.error(err.message);
    }))
    .pipe(postcss([autoprefixer, cssnano]))
    .pipe(gulp.dest('../css'))
    .on('end', done);

  gulp.src(
	  ['../js/**/*.js']
  	)
    .pipe(eslint({
		overrideConfig: {
			"extends": "standard",
			"ignorePatterns": ["swiper-bundle.min.js"],
		},
		"rules": {
			"semi": ["error", "always"],
			"no-unused-vars": "off",
			"no-undef": "off"
    	}
	}))
	.pipe(eslint.format())
	.pipe(eslint.failAfterError())
    .on('end', done);
});

gulp.task('watch', gulp.series('build', function (done) {
  gulp.watch(['../scss/**/*.scss', '../js/**/*.js'], gulp.parallel('build'));
  done();
}));

gulp.task('default', gulp.series('watch', function () {}));