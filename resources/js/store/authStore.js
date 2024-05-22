/* 

space
 */
import auth from "@/api/auth"
import { defineStore } from 'pinia'



export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    loading: false,
    is_logged_in: false,
  }),
  actions: {
    async login(payload) {
      this.loading = true
      try {

        await auth.useCSRF()


        //  debugger;
        const response = await auth.useLogin(payload)

        console.log(response)

        this.user = response
        this.is_logged_in = true

        localStorage.setItem('user', JSON.stringify(response))
        localStorage.setItem('is_logged_in', true)
          
        //alert("login");
          
      } catch (error) {
        console.log("error", error.response.data.error)
        this.is_logged_in = false
        this.loading = false
        
        return error.response.data.error
      }
      this.loading = false
    },
    async logout() {
      console.log("logout")
      this.loading = true
      try {
        await auth.useLogOut({})
        this.is_logged_in = false

        localStorage.removeItem('is_logged_in')
        localStorage.removeItem('user')

      } catch (error) {
        this.is_logged_in = true
        this.loading = false
      }
      this.loading = false
    },
    loadUserFromLocalStorage() {
      const user = localStorage.getItem('user')
      const is_logged_in = localStorage.getItem('is_logged_in')
      if (user) {
        this.user = JSON.parse(user)
        this.is_logged_in = is_logged_in === 'true'
      }

      return this.user
    },
  },
})
