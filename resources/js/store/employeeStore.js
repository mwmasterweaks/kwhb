/* 

space
 */
import api from '@/api/employee/employee'
import { defineStore } from 'pinia'
import { useAuthStore } from './authStore'

export const useEmployeeStore = defineStore('employees', {
  state: () => ({
    data: {
      employees: [],
      employee_selected: {},
      routeTab: 'EmployeeInfo', 
      widget: [
        {
          title: 'Active Employees',
          value: '0',
          change: 0,
          desc: 'Active Total Employees',
          icon: 'tabler-user',
          iconColor: 'primary',
        },
        {
          title: 'Offswing',
          value: '0',
          change: 0,
          desc: 'Fixed Period Contractors',
          icon: 'tabler-user-plus',
          iconColor: 'error',
        },
        {
          title: ' Pending Employees',
          value: '0',
          change: 0,
          desc: 'Year to Date',
          icon: 'tabler-user-check',
          iconColor: 'success',
        },
        {
          title: 'Extended Leave',
          value: '0',
          change: 0,
          desc: 'Year to Date',
          icon: 'tabler-user-exclamation',
          iconColor: 'warning',
        },
      ],
      statuses: [
        { title: 'Active', value: 'active' }, 
        { title: 'Offswing', value: 'offswing' }, 
        { title: 'Pending', value: 'pending' }, 
        { title: 'Extended Leave', value: 'extended leave' }, 
        { title: 'Inactive', value: 'inactive' },
        { title: 'Terminated', value: 'terminated' }, 
      ]
    },
  }),
  getters: {
    activeTab: state => state.data.routeTab,
  },
  actions: {
    async setActiveTab(tab) {
      console.log('emp store - set active tab', tab)
      this.data.routeTab = tab
    },
    async addEmployee(employee) {
      try {
        const response = await api.addEmployee(employee)

        console.log("addEmployee", response.data)

        if(!response.error){
          this.data.employees = response.data
          console.log("employees updated")
          
          return response.data
        }
        else 
          return false
                
      } catch (error) {
        console.error("Error adding employee:", error)
      }
    },
    async updateRow(data)
    {
      try {
        const response = await api.updateRow(data)

        console.log("updateRow", response)
        if(!response.error){
          this.data.employees = response.data
          
          return response.data
        }
        else 
          return false
                
      } catch (error) {
        console.error("Error adding employee:", error)
      }
    },
    async updateEmergencyContact(data)
    {
      try {
        const response = await api.updateEmergencyContact(data)

        console.log("updateEmergencyContact", response)
        if(!response.error){
          this.data.employees = response.data
          
          return response.data
        }
        else 
          return false
                
      } catch (error) {
        console.error("Error adding employee:", error)
      }
    },
    async updateMedical(data)
    {
      try {
        const response = await api.updateMedical(data)

        console.log("updateMedical", response)
        if(!response.error){
          this.data.employees = response.data
          
          return response.data
        }
        else 
          return false
                
      } catch (error) {
        console.error("Error updateMedical employee:", error)
      }
    },
    async updateAddress(data)
    {
      try {
        const response = await api.updateAddress(data)

        console.log("updateAddress", response)
        if(!response.error){
          this.data.employees = response.data
          
          return response.data
        }
        else 
          return false
                
      } catch (error) {
        console.error("Error updating Address employee:", error)
      }
    },
    async addBankInfo(param) {
      try {
        const response = await api.addBankInfo(param)

        this.data.employee_selected.bank_info = response.data

        if(!response.error)
          return response.data
        else 
          return false
      } catch (error) {
        console.error("Error fetching employees:", error)
      }
    },
    async fetchWidgetData() {
      try {
        const response = await api.fetch_widget_data()
        if(!response.error){
          this.data.widget = response.data
          console.log("widget updated")
          
          return response.data
        }
        else 
          return false
      } catch (error) {
        console.log("Error fetch_widget_data:", error)
      }
    },
    async fetch_approvers(param) {
      try {
        const response = await api.fetch_approvers(param)

        console.log("fetch approvers: ", response)
        
        return response.data
      } catch (error) {
        console.log("Error fetching approvers:", error)
      }
    },
    async setEmployees(params) {
      const authStore = useAuthStore()
      try {
        const response = await api.fetchEmployees(params)

        console.log("setEmployees", response)
        this.data.employees = response.data
        return response;
      } catch (error) {
        authStore.logout()

        console.log("Error fetching employees:", error)
      }
    },
    async multipleFilter(payload) {
      const response = await api.multipleFilter(payload);
      console.log("multipleFilter", response);
      return response.data;
    },
    async updateBankInfo (param) {

      try {
        const response = await api.updateBankInfo(param)

        this.data.employee_selected.bank_info = response.data
        if(!response.error)
          return response.data
        else 
          return false
      } catch (error) {
        console.error("Error fetching employees:", error)
      }
    },
    async fetch_employee_by_name(param) {
      try {
        const response = await api.fetch_employee_by_name(param)
        return response.data
      } catch (error) {
        console.log("Error fetch_employee_by_name:", error)
      }
    },
        
  },
})
