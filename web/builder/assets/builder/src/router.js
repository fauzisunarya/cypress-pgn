import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home.vue'
import Builder from './views/Builder.vue'
import Testing from './views/Testing.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/home',
      name: 'home',
      component: Home
    },
    {
      path: '/',
      name: 'builder',
      // route level code-splitting
      // this generates a separate chunk (about.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: Builder
    },
    {
      path: '/testing',
      name: 'builder',
      component: Testing
    }
  ]
})
