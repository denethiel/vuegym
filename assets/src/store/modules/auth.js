//import gym from '../axios'
import axios from 'axios'

const state = {
	token: localStorage.getItem('token') || '',
	status: '',
	user: {}
}

const getters = {
	isLoggedIn: state => !!state.token,
	authStatus: state => state.status
}

const api = axios.create({
	baseUrl: 'http://localhost:8888/vuegym/v1'
})

const actions = {
	login({ commit }, user) {
		return new Promise((resolve, reject) => {
			commit('auth_request')
			console.log(user.usuario)
			console.log(user.password)
			axios({
				method: 'POST',
				url: 'http://localhost:8888/vuegym/api/users/login',
				data: {
					usuario: user.usuario,
					password: user.password
				},
				headers: {
					'Content-Type': 'application/json',
					'Access-Control-Allow-Origin': '*'
				}
			})
				// fetch('http://localhost:8888/vuegym/api/users/login', {
				// 	method: 'POST',
				// 	body: JSON.stringify(user),
				// 	headers: {
				// 		'Content-Type': 'application/json'
				// 	}
				// })
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
	logout({ commit }) {
		return new Promise(resolve => {
			commit('logout')
			localStorage.removeItem('token')
			resolve()
		})
	}
}

const mutations = {
	auth_request(state) {
		state.status = 'loading'
	},
	auth_success(state, token, user) {
		state.status = 'success'
		state.token = token
		state.user = user
	},
	auth_error(state) {
		state.status = 'error'
	},
	logout(state) {
		state.status = ''
		state.token = ''
	}
}

export default {
	state,
	getters,
	actions,
	mutations
}
