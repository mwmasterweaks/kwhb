import { setupLayouts } from 'virtual:generated-layouts'
import { createRouter, createWebHistory } from 'vue-router'

// import { redirects } from './additional-routes'

import routes from '~pages'


const router = createRouter({
  history: createWebHistory('/'),
  routes: [...setupLayouts(routes)],
})

// Global navigation guard
router.beforeEach((to, from, next) => {
  const loggedIn = localStorage.getItem('is_logged_in') ?? false
  if (to.name === 'login') {
    // If the user is already logged in, redirect to the home page
    if (loggedIn) {
      next({ path: '/' })
    } else {
      next() // Proceed to login page
    }
  } else {
    // If the user is not logged in, redirect to the login page
    if (!loggedIn && to.name !== 'login') {
      next({ name: 'login' })
    } else {
      next() // Proceed to the requested route
    }
  }
})


// Docs: https://router.vuejs.org/guide/advanced/navigation-guards.html#global-before-guards
export default router
