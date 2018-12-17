const path = require('path')
const VueLoaderPlugin = require('vue-loader/lib/plugin')

var entryPoint = {
	app: './assets/src/main.js'
}

var exportPath = path.resolve(__dirname, './assets/js')

module.exports = {
	entry: entryPoint,
	output: {
		path: exportPath,
		filename: '[name].js'
	},
	module: {
		rules: [{
				test: /\.vue$/,
				loader: 'vue-loader'
			},
			{
				enforce: 'pre',
				test: /\.(js|vue)$/,
				loader: 'eslint-loader',
				exclude: /node_modules/
			},
			{
				test: /\.js$/,
				exclude: file => (
					/node_modules/.test(file) &&
					!/\.vue\.js/.test(file)
				),
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-env']
					}
				}
			},
			{
				test: /\.less$/,
				use: [
					'vue-style-loader',
					'css-loader',
					'less-loader'
				]
			},
			{
				test: /\.css$/,
				loader: 'style-loader!css-loader'
			},
			{
				test: /\.(png|jpg|jpeg|gif|eot|ttf|woff|woff2|svg|svgz)(\?.+)?$/,
				use: [{
					loader: 'url-loader',
					options: {
						limit: 10000
					}
				}]
			}
		]
	},
	plugins: [
		new VueLoaderPlugin()
	]
}
