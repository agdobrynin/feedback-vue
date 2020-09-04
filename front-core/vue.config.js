module.exports = {
    publicPath: '/js/',
    outputDir: `${__dirname}/../public/js/`,
    filenameHashing: true,
    pages: {
        feedbackForm: {
            entry: 'src/Form/main.js',
            // source template
            template: 'src/Form/index.html',
            // output
            filename: 'feedbackForm.html',
            chunks: ['chunk-vendors', 'chunk-common', 'feedbackForm']
        },
        feedbackList: {
            entry: 'src/List/main.js',
            // source template
            template: 'src/List/index.html',
            // output
            filename: 'feedbackList.html',
            chunks: ['chunk-vendors', 'chunk-common', 'feedbackList']
        },
    },
    devServer: {
        inline: false
    },
}
