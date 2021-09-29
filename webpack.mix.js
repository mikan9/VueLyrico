const path = require('path');
const mix = require('laravel-mix');

mix.extend('i18n', new class {
    webpackRules() {
        return [
            {
                resourceQuery: /blockType=i18n/,
                type: 'javascript/auto',
                loader: '@kazupon/vue-i18n-loader',
            },
        ];
    }
}(),
);


mix.i18n()
    .js('frontend/src/main.js', 'public/js/app.js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css')
    .alias({
        '@': path.join(__dirname, 'frontend/src')
    })
    .copy("frontend/src/assets/images", "public/images/");