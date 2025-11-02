const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const IgnoreEmitPlugin = require('ignore-emit-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');

const entries = {
    app: './assets/scripts/app.ts',
    styles: './assets/styles/app.scss',
}
const ignoreFiles = Object.keys(entries).reduce((acc, key) => {
    if (entries[key].endsWith('.scss')) {
        acc.push(`${key}.js`);
    }

    return acc;
}, []);

module.exports = (env, options) => {
    const isProduction = options.mode === 'production';

    return {
        entry: entries,
        module: {
            rules: [
                {
                    test: /\.ts?$/,
                    use: 'ts-loader',
                    exclude: /node_modules/
                },
                {
                    test: /\.s[ac]ss$/i,
                    use: [
                        MiniCssExtractPlugin.loader,
                        'css-loader',
                        'postcss-loader',
                        'sass-loader'
                    ],
                    exclude: /node_modules/
                },
            ],
        },
        plugins: [
            new MiniCssExtractPlugin({
                filename: 'styles/[name].css',
            }),
            new IgnoreEmitPlugin(ignoreFiles)
        ],
        optimization: isProduction ? {
            minimize: true,
            minimizer: [
                new TerserPlugin(),
                new CssMinimizerPlugin(),
            ]
        } : {},
        resolve: {
            extensions: ['.ts', '.js', '.css', '.scss']
        },
        watchOptions: {
            poll: true,
            ignored: /node_modules/
        },
        output: {
            filename: 'scripts/[name].js',
            path: path.resolve(__dirname, 'public/assets/')
        }
    };
}