    const autoprefixer = require("autoprefixer");
    const MiniCSSExtractPlugin = require("mini-css-extract-plugin");
    const CSSMinimizerPlugin = require("css-minimizer-webpack-plugin");
    const TerserPlugin = require("terser-webpack-plugin");

    const path = require("path");
    const admin = path.join(__dirname, "src", "admin");
    const front = path.join(__dirname, "src", "front");
    const blocks = path.join(__dirname, "src", "blocks");

    const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

    module.exports = (env, argv) => {
        function isDevelopment() {
            return argv.mode === "development";
        }
        var config = {
            ...defaultConfig,
            ...{
            entry: {
                blocks: blocks,
                admin: admin,
                front: front,
            },
            output: {
                path: path.resolve(__dirname, "build"),
                filename: ({ chunk: { name } }) => {
                    if (name === 'blocks') {
                        return 'blocks/index.js';  // Adjusted here
                    } else if (name === 'admin') {
                        return '[name].js';
                    } else if (name === 'front') {
                        return '[name].js';
                    }
                    return '[name].js';
                },
                clean: true,
            },
            optimization: {
                minimizer: [
                    new CSSMinimizerPlugin(),
                    new TerserPlugin({ terserOptions: { sourceMap: true } }),
                ],
                ...defaultConfig.optimization,
            },
            plugins: [
                ...defaultConfig.plugins,
                new MiniCSSExtractPlugin({
                    chunkFilename: "[id].css",
                    filename: (chunkData) => {
                        let name = chunkData.chunk.name;
                        if (name === 'blocks') {
                            return 'blocks/index.css';  // Adjusted here
                        } else if (name === 'admin') {
                            return '[name].css';
                        } else if (name === 'front') {
                            return '[name].css';
                        }
                        return '[name].css';
                    },
                }),
            ],
            devtool: isDevelopment() ? "cheap-module-source-map" : "source-map",
            module: {
                rules: [
                    {
                        test: /\.js$/,
                        exclude: /node_modules/,
                        use: [
                            {
                                loader: "babel-loader",
                                options: {
                                    presets: ["@babel/preset-env"],
                                },
                            },
                        ],
                    },
                    {
                        test: /\.(sa|sc|c)ss$/,
                        use: [
                            MiniCSSExtractPlugin.loader,
                            "css-loader",
                            {
                                loader: "postcss-loader",
                                options: {
                                    postcssOptions: {
                                        plugins: [autoprefixer()],
                                    },
                                },
                            },
                            "sass-loader",
                        ],
                    },

                ],
                ...defaultConfig.module,
            },
        }
    };
        return config;
    };
