const path = require('path');
const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('frontend/src/main.js', 'public/js/app.js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css')
    .alias({
        '@': path.join(__dirname, 'frontend/src')
    });