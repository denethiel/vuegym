import axios from 'axios'

const baseUrl = 'http://localhost:8888/vuegym/v1'
const headers = {
	'Access-Control-Allow-Origin': '*',
	'Content-Type': 'application/x-www-form-urlencoded'
}

export default {
	apiCall: function(url, data, method) {
		console.log(data)
		return new Promise((resolve, reject) => {
			axios({
				url: baseUrl + url,
				method: method,
				headers: headers
			})
				.then(resp => {
					console.log(resp)
					resolve(resp)
				})
				.catch(err => {
					console.log(err)
					reject(err)
				})
		})
	},
	setToken: function(token) {
		return new Promise(resolve => {
			axios.defaults.headers.common['Authorization'] = token
			resolve()
		})
	},
	deleteToken: function() {
		return new Promise(resolve => {
			delete axios.defaults.headers.common['Authorization']
			resolve()
		})
	}
}
