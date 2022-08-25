const mix = require('laravel-mix')
const path = require('path');

mix
  .js('resources/js/app.js', 'public/js')
  .sass('resources/scss/app.scss', 'public/css')
  .options({
    postCss: [
      require('postcss-import'),
      require('tailwindcss'),
      require('postcss-nested'),
      require('autoprefixer')
    ]
  })
  .webpackConfig({
    plugins: [
      new (require('purge-icons-webpack-plugin').default)
    ],
    output: {
      chunkFilename: 'js/[name].js?id=[chunkhash]'
    },
    resolve: {
      alias: {
        vue$: 'vue/dist/vue.runtime.esm.js',
        '@': path.resolve('resources/js')
      }
    }
  })
  .vue({ version: 2 })
  .sourceMaps()
  .version()
