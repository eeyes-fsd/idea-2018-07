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

mix.webpackConfig({
    resolve: {
      alias: {
        /**
         * 路径解析，import时字符@表示vue目录
         */
        '@': path.resolve(__dirname, 'vue')
      }
    }
  })
  /**
   * 资源打包
   */
  .js('vue/views/home/index.js', 'public/js/home.js')
  .js('vue/views/admin/index.js', 'public/js/admin.js')
  .sass('resources/assets/sass/app.scss', 'public/css');
