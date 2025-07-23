const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const webpack = require("webpack");
const CopyPlugin = require("copy-webpack-plugin");

// console.log(defaultConfig.entry())
module.exports = {
    ...defaultConfig,
    entry: {
        ...defaultConfig.entry(),
        admin: "./src/admin/index.js",
    },
    performance: {
        maxEntrypointSize: 5120000,
        maxAssetSize: 5120000,
    },
    plugins: [
        ...defaultConfig.plugins,
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery",
        }),
        new CopyPlugin({
            patterns: [
                {
                    from: "./assets/img",
                    to: "img/[path]/[name][ext]",
                    noErrorOnMissing: true,
                },
            ],
        }),
        new webpack.ProvidePlugin({
            ScrollMagic: "scrollmagic",
            "debug.addIndicators":
                "ScrollMagic/scrollmagic/uncompressed/plugins/debug.addIndicators",
        }),
    ],
    module: {
        ...defaultConfig.module,
        rules: [
            ...defaultConfig.module.rules,
            {
                test: /\.html$/,
                use: "html-loader",
            },
            {
                test: /\.(svg|png|jpg|gif)$/,
                type: "asset/resource",
                generator: {
                    filename: (name) => {
                        /**
                         * @description Remove first & last item from ${path} array.
                         * @example
                         *      Orginal Path: 'assets/images/avatar/image.jpg'
                         *      Changed To: 'images/avatar'
                         */
                        const path = name.filename
                            .split("/")
                            .slice(1, -1)
                            .join("/");
                        return `${path}/[name][ext]`;
                    },
                },
            },
            {
                test: /\.scss$/,
                use: [
                    'postcss-loader',
                    'sass-loader',
                    {
                        loader: 'sass-resources-loader',
                        options: {
                            resources: './src/scss/config/config.scss'
                        }
                    }
                ]
            }
        ],
    },
};