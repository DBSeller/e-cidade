const mix = require('laravel-mix');
const EncodingPlugin = require('webpack-encoding-plugin');
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


mix.js(
    'resources/js/extension/package/desktop/views/desktop/index.js',
    'public/extension/package/desktop/views/desktop')
    .sass(
        'resources/sass/app.scss',
        'public/css'
    )
    .js('vue/modulo/rh/recadastramento.js',
        'public/vue/modulo/rh'
    )
    .webpackConfig({
        plugins: [
            new EncodingPlugin({
                encoding: 'iso-8859-1',
            }),
        ],
    }).vue({ version: 3 });
