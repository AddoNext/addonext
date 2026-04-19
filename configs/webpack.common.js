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
                {
                    from: "./src/img/icons/*.svg",
                    to: "img/icons/[name][ext]",
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
            {
                test: /\.html$/,
                use: "html-loader",
            },
            {
                test: /\.(woff2?|ttf|eot)$/,
                type: "asset/resource",
                generator: {
                    filename: "webfonts/[name][ext]",
                },
            },
            {
                test: /\.(svg|png|jpg|gif)$/,
                type: "asset/resource",
                generator: {
                    filename: (name) => {
                        const isIcon = name.filename.includes('/img/icons/');
                        if (isIcon) return 'img/icons/[name][ext]';
                        const path = name.filename
                            .split("/")
                            .slice(1, -1)
                            .join("/");
                        return `${path}/[name][ext]`;
                    },
                },
            },
            ...defaultConfig.module.rules,
            {
                test: /\.scss$/,
                // exclude: /\.module\.scss$/,
                use: [
                    'postcss-loader',
                    {
                        loader: 'sass-loader',
                    },
                    {
                        loader: 'sass-resources-loader',
                        options: {
                            resources: require('path').resolve(__dirname, '../src/scss/config/config.scss')
                        }
                    }
                ]
            }
        ],
    },
};