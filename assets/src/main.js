import 'babel-polyfill'
import Vue from 'vue'
import App from './components/App.vue'
import store from './store'
import router from './router'
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.config.productionTip = false

Vue.use(BootstrapVue)

new Vue({
	el: '#app',
	router,
	store,
	render: h => h(App)
})
