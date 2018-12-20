// WebPack V4

// Import modules
const path = require("path");
// Plugins
const UglifyJSPlugin = require("uglifyjs-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");

// Entry
const entry = {
  app: "./wp-content/themes/aj_portfolio/assets/js/index.js",
  styles: "./wp-content/themes/aj_portfolio/assets/scss/index.scss"
};

// Module
const modules = {
  rules: [
    {
      test: /\.js$/,
      exclude: /node_modules/,
      use: {
        loader: "babel-loader"
      }
    },
    {
      test: /\.(sass|scss)$/,
      use: [MiniCssExtractPlugin.loader, "css-loader", "sass-loader"]
    }
  ]
};

// Resolve
const resolve = {
  extensions: [".js", ".jsx", ".scss"],
  descriptionFiles: ["package.json"]
};

// Output
const output = {
  filename: "./wp-content/themes/aj_portfolio/assets/js/[name].min.js",
  path: path.resolve(__dirname)
};

// Plugin
const plugins = [
  new MiniCssExtractPlugin({
    filename: "./wp-content/themes/aj_portfolio/assets/css/[name].min.css"
  })
];

// Optimization
const optimization = {
  minimizer: [
    new UglifyJSPlugin({
      cache: true,
      parallel: true
    }),
    new OptimizeCSSAssetsPlugin({})
  ]
};

// ##### EXPORT #####
module.exports = {
  target: "web",
  entry: entry,
  output: output,
  module: modules,
  resolve: resolve,
  plugins: plugins,
  optimization: optimization
};
