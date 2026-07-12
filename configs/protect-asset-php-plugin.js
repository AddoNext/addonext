/**
 * Prepends WordPress direct-access protection to webpack-generated .asset.php files.
 */
class ProtectAssetPhpPlugin {
    /**
     * @param {import('webpack').Compiler} compiler
     */
    apply(compiler) {
        const { RawSource } = compiler.webpack.sources;

        compiler.hooks.compilation.tap('ProtectAssetPhpPlugin', (compilation) => {
            compilation.hooks.processAssets.tap(
                {
                    name: 'ProtectAssetPhpPlugin',
                    stage: compiler.webpack.Compilation.PROCESS_ASSETS_STAGE_REPORT,
                },
                (assets) => {
                    for (const filename of Object.keys(assets)) {
                        if (!filename.endsWith('.asset.php')) {
                            continue;
                        }

                        const original = assets[filename].source().toString();
                        if (/defined\s*\(\s*['"]ABSPATH['"]\s*\)/.test(original)) {
                            continue;
                        }

                        const protectedSource = original.replace(
                            /^<\?php\s*/,
                            "<?php\nif ( ! defined( 'ABSPATH' ) ) {\n\texit;\n}\n"
                        );

                        if (protectedSource === original) {
                            continue;
                        }

                        compilation.updateAsset(filename, new RawSource(protectedSource));
                    }
                }
            );
        });
    }
}

module.exports = ProtectAssetPhpPlugin;
