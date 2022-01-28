const { defineConfig } = require('@vue/cli-service')
module.exports = defineConfig({
  publicPath: './',
  outputDir: '/var/www/apps.arfevrier.fr/youtube_player',
  transpileDependencies: [
    'vuetify'
  ]
})
