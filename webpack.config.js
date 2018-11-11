const path = require('path');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer')
  .BundleAnalyzerPlugin;

module.exports = (env, args) => {
  const CONFIG = {
    entry: './Resources/Private/Assets/JavaScript/index.js',
    output: {
      filename: 'PoiMapInit.min.js',
      path: path.resolve(__dirname, 'Resources/Public/JavaScript')
    },
    module: {
      rules: [
        {
          enforce: 'pre',
          test: /\.js$/,
          exclude: /node_modules/,
          loader: 'eslint-loader'
        },
        {
          test: /.js$/,
          use: {
            loader: 'babel-loader',
            options: {
              presets: ['@babel/preset-env']
            }
          }
        },
        {
          test: /.css$/,
          use: ['style-loader', 'css-loader']
        }
      ]
    },
    resolve: {
      alias: {
        '@': __dirname
      }
    },
    plugins: [],
    devtool: args.mode !== 'production' ? 'eval-source-map' : false
  };

  if (env && env.BUILD_ADDON === 'analyze') {
    CONFIG.plugins.push(new BundleAnalyzerPlugin());
  }

  return CONFIG;
};
