const mix = require('laravel-mix');
const path = require('path');
const autoprefixer = require('autoprefixer');
const LiveReloadPlugin = require('webpack-livereload-plugin');
const StylelintPlugin = require('stylelint-webpack-plugin');

const appDir = path.resolve(__dirname, 'resources/assets/app');

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

const extendedConfig = {
  resolve: {
    extensions: [ '.js', '.vue', '.json', '.scss' ],
    modules:    [ appDir, 'node_modules' ],
  },
  module: {
    rules: [
      {
        test:    /\.(js|vue)$/,
        loader:  'eslint-loader',
        enforce: 'pre',
        include: [appDir],
      },
    ],
  },
  plugins: [
    new LiveReloadPlugin(),
    new StylelintPlugin({
      files: [
        `${appDir}/**/*.scss`,
        `${appDir}/**/*.vue`,
        `${appDir}/**/*.css`,
      ],
    }),
  ],
};

//
mix
  .js(`${appDir}/main.js`, 'public/assets/js')
  .sass('resources/assets/sass/app.scss', 'public/assets/styles')
  .copyDirectory('resources/assets/img', 'public/assets/img')
  .copyDirectory('resources/assets/fonts', 'public/assets/fonts')
  .disableNotifications()
  .options({
    postCss: [autoprefixer()],
  })
  .version();

if (!mix.inProduction()) {
  mix.sourceMaps();
}

mix.webpackConfig(extendedConfig);

module.exports = extendedConfig;
