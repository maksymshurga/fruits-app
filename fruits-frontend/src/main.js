import Vue from 'vue'
import VueRouter from 'vue-router'
import vuetify from './plugins/vuetify'
import App from './App.vue'
import FruitsComponent from './components/FruitsComponent'
import FavoritesComponent from './components/FavoritesComponent'

Vue.config.productionTip = false

Vue.use(VueRouter)

const routes = [
  { path: '/', component: FruitsComponent },
  { path: '/favorites', component: FavoritesComponent }
]

const router = new VueRouter({
  mode: 'history',
  routes
})

new Vue({
  router,
  render: h => h(App),
  vuetify
}).$mount('#app')
