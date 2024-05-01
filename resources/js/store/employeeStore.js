/* 

space
 */
import api from '@/api/employee/employee';
import { defineStore } from 'pinia';
import { useAuthStore } from './authStore';

export const useEmployeeStore = defineStore('employees', {
    state: () => ({
      data:{
        employees:[],
        employee_selected: {}
      }
    }),
    actions: {
        async addEmployee(employee) {
          try {
            const response = await api.addEmployee(employee);
            console.log("addEmployee", response.data);

            if(!response.error){
                this.data.employees = response.data;
                console.log("employees updated");
                return response.data
            }
            else 
                return false
                
          } catch (error) {
            console.error("Error adding employee:", error);
          }
        },
        async updateRow(data)
        {
          try {
            const response = await api.updateRow(data);
            console.log("updateRow", response);
            if(!response.error){
                this.data.employees = response.data;
                return response.data
            }
            else 
                return false
                
          } catch (error) {
              console.error("Error adding employee:", error);
          }
        },
        async updateEmergencyContact(data)
        {
          try {
            const response = await api.updateEmergencyContact(data);
            console.log("updateEmergencyContact", response);
            if(!response.error){
                this.data.employees = response.data;
                return response.data
            }
            else 
                return false
                
          } catch (error) {
              console.error("Error adding employee:", error);
          }
        },
        async updateMedical(data)
        {
          try {
            const response = await api.updateMedical(data);
            console.log("updateMedical", response);
            if(!response.error){
                this.data.employees = response.data;
                return response.data
            }
            else 
                return false
                
          } catch (error) {
              console.error("Error updateMedical employee:", error);
          }
        },
        async updateAddress(data)
        {
          try {
            const response = await api.updateAddress(data);
            console.log("updateAddress", response);
            if(!response.error){
                this.data.employees = response.data;
                return response.data
            }
            else 
                return false
                
          } catch (error) {
              console.error("Error updating Address employee:", error);
          }
        },
        async addBankInfo(param) {
          try {
              const response = await api.addBankInfo(param);
              this.data.employee_selected.bank_info = response.data;

              if(!response.error)
                return response.data
              else 
                  return false
          } catch (error) {
              console.error("Error fetching employees:", error);
          }
        },
        async updateBankInfo(param) {
          try {
              const response = await api.updateBankInfo(param);
              this.data.employee_selected.bank_info = response.data;
              if(!response.error)
                return response.data
              else 
                  return false
          } catch (error) {
              console.error("Error fetching employees:", error);
          }
        },
        async fetch_employee_by_name(param) {
          try {
            const response = await api.fetch_employee_by_name(param);
            return response.data;
          } catch (error) {
              console.log("Error fetching approvers:", error);
          }
        },
        async fetch_approvers(param) {
          try {
            const response = await api.fetch_approvers(param);
            console.log("fetch approvers: ", response);
            return response.data;
          } catch (error) {
              console.log("Error fetching approvers:", error);
          }
        },
        async setEmployees() {
          const authStore = useAuthStore();
          try {
              const response = await api.fetchEmployees();
              console.log("setEmployees", response);
              this.data.employees = response;
          } catch (error) {
              authStore.logout();

              console.log("Error fetching employees:", error);
          }
        }
        
    }
})
