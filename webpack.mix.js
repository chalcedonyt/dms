let mix = require('laravel-mix');

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

mix.react('resources/assets/js/app.js', 'public/js')
    .extract([
      'axios',
      'prop-types',
      'query-string',
      'react',
      'react-bootstrap',
      'react-dom',
      'react-loadable',
      'react-router-dom',
    ]);

mix.react('resources/assets/js/admins.js', 'public/js');
mix.react('resources/assets/js/lists.js', 'public/js');
mix.react('resources/assets/js/vouchers.js', 'public/js');
mix.react('resources/assets/js/members.js', 'public/js');

mix.less('resources/assets/less/app.less', 'public/css');
if (mix.inProduction()) {
  mix.version()
}