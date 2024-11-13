const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')

    .setPublicPath('/build')

    .addEntry('app_js', './assets/app.js')
    .addStyleEntry('home_css', './assets/styles/home.scss')
    .enableStimulusBridge('./assets/controllers.json')

    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .enableSassLoader()
    .enablePostCssLoader()

    .cleanupOutputBeforeBuild()

    .enableBuildNotifications()

    .enableSourceMaps(!Encore.isProduction())

    .enableVersioning(Encore.isProduction())

    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-transform-class-properties');
    })
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    .copyFiles({
        from: './assets/img',
        to: 'img/[path][name].[hash:8].[ext]',
    });

module.exports = Encore.getWebpackConfig();
