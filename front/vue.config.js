const { defineConfig } = require('@vue/cli-service')
module.exports = defineConfig({
  transpileDependencies: true
})

// vue.config.js
module.exports = {
  devServer: {
    proxy: {
      '/commissions/api': {
        target: 'https://127.0.0.1:8000',
        secure: false,
        changeOrigin: true
      }
    }
  }
};
