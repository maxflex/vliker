import Vue from 'vue'
import Router from 'vue-router'
import IndexPage from '@/pages/Index'
import GoPage from '@/pages/Go'
import StatsPage from '@/pages/Stats'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'IndexPage',
      component: IndexPage,
      meta: {
        transition: 'fade-in-left'
      }
    },
    {
      path: '/go',
      name: 'GoPage',
      component: GoPage,
      meta: {
        transition: 'zoom',
        noHeader: true,
      }
    },
    {
      path: '/stats',
      name: 'StatsPage',
      component: StatsPage,
      meta: {
        title: 'Мои накрутки',
        backButton: true,
        transition: 'fade-in-right',
      }
    },
    // ...require('./cabinets').default,
  ]
})

export default router