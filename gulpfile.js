var gulp = require('gulp');
var elixir = require('laravel-elixir');
require('laravel-elixir-vue-2');
require('laravel-elixir-webpack-official');

gulp.task('default', function() {
    // place code for your default task here
});

elixir(function(mix) {
    var bpathsass = 'node_modules/bootstrap-sass/assets';
    var bpath = 'node_modules/bootstrap/dist/js';
    var jqueryPath = 'resources/assets/vendor/jquery';
    var vuePath = 'node_modules/vue/dist/vue.js';
    var axiosPath = 'node_modules/axios/dist/axios.js';
    mix.sass('*.scss', 'public_html/css');
    mix.sass('main.scss','public_html/css');
    mix.sass('chat.scss','public_html/css');
    mix.sass('dark.scss','public_html/css')
        .copy(jqueryPath + '/dist/jquery.min.js', 'public_html/js')
        .copy(bpath + '/*.js', 'public_html/js')
        .copy(bpathsass + '/fonts', 'public_html/fonts')
        .copy(vuePath, 'public_html/js')
        .copy(axiosPath, 'public_html/js')
        .copy('resources/assets/js/*.js', 'public_html/js')
        .copy('resources/assets/js/chat/*.js', 'public_html/js/chat')
        .copy('node_modules/@fortawesome/fontawesome-free/css/*.css', 'public_html/css/fontawesome')
        .copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public_html/css/webfonts')
});
