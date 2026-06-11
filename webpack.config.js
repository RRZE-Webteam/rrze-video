const path = require("path");
const admin = path.join(__dirname, "src", "admin");
const front = path.join(__dirname, "src", "front");
const blocks = path.join(__dirname, "src", "blocks");

const defaultConfig = require("@wordpress/scripts/config/webpack.config");

// Import the helper to find and generate the entry points in the src directory
const { getWebpackEntryPoints } = require("@wordpress/scripts/utils/config");

// Check if it's a production build
const isProduction = process.env.NODE_ENV === "production";

let optimization = defaultConfig.optimization;

if (isProduction) {
  optimization = {
    ...defaultConfig.optimization,
  };
}

// Set the devtool based on the build environment
const devtool = isProduction ? false : "source-map";

module.exports = {
  ...defaultConfig,
  entry: {
    ...getWebpackEntryPoints("script")(),
    blocks: blocks,
    admin: admin,
    front: front,
  },
  devtool: devtool,
  module: {
    ...defaultConfig.module,
    rules: [
      ...defaultConfig.module.rules,
      {
        test: /\.js$/,
        include: /node_modules\/@vidstack/,
        use: {
          loader: "babel-loader",
          options: {
            presets: [
              ["@babel/preset-env", { targets: { chrome: 58, ie: 11 } }],
              "@babel/preset-react",
            ],
            plugins: [
              "@babel/plugin-transform-private-methods",
              "@babel/plugin-transform-class-properties",
            ],
          },
        },
      },
    ],
  },
  // Erweitern Sie die Dateierweiterungen, die Webpack verarbeiten wird
  resolve: {
    ...defaultConfig.resolve,
    extensions: [".tsx", ".ts", ".js", ".json"],
  },
  optimization: optimization,
  performance: {
    ...defaultConfig.performance,
    hints: false,
  },
};
