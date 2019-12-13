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
mix.setPublicPath('./');

mix.options({
    processCssUrls: false
});

mix.js('resources/js/homepage.js', 'public_html/js')
    .js('resources/js/tree.js', 'public_html/js')
    .js('resources/js/addMember.js', 'public_html/js')
    .js('resources/js/updateMember.js', 'public_html/js');

mix.sass('resources/sass/main.scss', 'public_html/css');
