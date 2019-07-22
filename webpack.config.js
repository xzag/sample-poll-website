const path = require('path')
const Dotenv = require('dotenv-webpack');

module.exports = {
  entry: './src/poll.js',
  output: {
    path: path.resolve(__dirname, 'public/js'),
    filename: 'poll.js',
    libraryTarget: 'var',
    library: 'Poll'
  },
  module: {
    rules: [{
      test: /\.js$/,
      exclude: /(node_modules|bower_components)/,
      use: {
        loader: 'babel-loader',
        options: {
          presets: ['@babel/preset-env']
        }
      }
    }]
  },
  plugins: [
    new Dotenv()
  ]
}
