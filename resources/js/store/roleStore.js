/* 

space
 */
import { fetchRoles } from '@/api/role/role';
import { defineStore } from 'pinia';

export const useRoleStore = defineStore('roles', {
    state: () => ({
      data:{
        roles:[]
      }
    }),
    actions: {
        addRoles(role) {
            this.data.roles.push(role)
        },
        async setRoles() {
          try {
              const response = await fetchRoles();
              this.data.roles = response;
          } catch (error) {
              console.error("Error fetching roles:", error);
          }
        }
    }
})
