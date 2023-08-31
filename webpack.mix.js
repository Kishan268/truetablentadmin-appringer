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

mix.setPublicPath('public')
    .setResourceRoot('../') // Turns assets paths in css relative to css file
    // .options({
    //     processCssUrls: false,
    // })
    .sass('resources/sass/frontend/app.scss', 'css/frontend.css')
    .sass('resources/sass/backend/app.scss', 'css/backend.css')
    .js('resources/js/frontend/app.js', 'js/frontend.js')
    .js([
        'resources/js/backend/before.js',
        'resources/js/backend/app.js',
        'resources/js/backend/after.js'
    ], 'js/backend.js')
    .extract([
        // Extract packages from node_modules to vendor.js
        'jquery',
        'bootstrap',
        'popper.js',
        'axios',
        'sweetalert2',
        'lodash'
    ])
    .sourceMaps();

// mix.scripts(['public/theme/js/jquery.min.js', 'public/theme/plugins/bootstrap/js/bootstrap.min.js', 'public/theme/plugins/bootstrap/js/bootsnav.js', 'public/theme/js/viewportchecker.js', 'public/theme/plugins/slick-slider/slick.js', 'public/theme/plugins/bootstrap/js/wysihtml5-0.3.0.js', 'public/theme/plugins/bootstrap/js/bootstrap-wysihtml5.js', 'public/theme/plugins/nice-select/js/jquery.nice-select.min.js', 'public/theme/js/custom.js'], 'public/js/themes.js');

if (mix.inProduction()) {
    mix.version()
        .options({
            // Optimize JS minification process
            terser: {
                cache: true,
                parallel: true,
                sourceMap: true
            }
        });
} else {
    // Uses inline source-maps on development
    mix.webpackConfig({
        devtool: 'inline-source-map'
    });
}
