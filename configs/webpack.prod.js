const path = require("path");
const webpack = require("webpack");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const common = require("./webpack.common");
const { mergeWithCustomize, customizeArray } = require("webpack-merge");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");

const bannerText = "Source: https://github.com/addonext/addonext | License: GPL-2.0+";

module.exports = mergeWithCustomize({
    // Replace WP scripts minimizers so a second Terser does not strip the banner
    customizeArray: customizeArray({
        "optimization.minimizer": "replace",
    }),
})(common, {
    mode: "production",
    output: {
        path: path.resolve(__dirname, "../dist/"),
        filename: "js/[name].min.js",
        assetModuleFilename: `css/[path][name][ext]`,
    },
    optimization: {
        minimizer: [
            new TerserPlugin({
                extractComments: false,
                terserOptions: {
                    compress: {
                        passes: 2,
                    },
                    mangle: {
                        // Match @wordpress/scripts so i18n helpers stay intact
                        reserved: ["__", "_n", "_nx", "_x"],
                    },
                    format: {
                        comments: /Source: https:\/\/github\.com\/addonext\/addonext/,
                    },
                },
            }),
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "css/[name].min.css",
        }),
        new CleanWebpackPlugin(),
        new webpack.BannerPlugin({
            banner: bannerText,
            raw: false,
            // Run after Terser (OPTIMIZE_SIZE) so the comment is not stripped
            stage: webpack.Compilation.PROCESS_ASSETS_STAGE_REPORT,
        }),
    ],
    module: {
        rules: [
            {
                test: /\.(scss|css)$/,
                exclude: [/\.module\.(scss|css)$/, /node_modules/],
                use: [MiniCssExtractPlugin.loader, "css-loader", "sass-loader"],
            },
            {
                test: /\.ttf$/,
                type: "asset/resource",
                generator: {
                    filename: "fonts/[name][ext]",
                },
            },
        ],
    },
});
