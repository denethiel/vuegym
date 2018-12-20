import 'babel-polyfill'
import Vue from 'vue'
import Axios from 'axios'
import App from './components/App.vue'
import store from './store'
import router from './router'
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import '../less/custom.less'

Vue.config.productionTip = false

Vue.prototype.$http = Axios;
const token = localStorage.getItem('token')
if(token){
	Vue.prototype.$http.defaults.headers.common['Authorization'] = token
}

Vue.use(BootstrapVue)

new Vue({
	el: '#app',
	router,
	store,
	render: h => h(App)
})
