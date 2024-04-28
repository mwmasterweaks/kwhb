import axios from '@axios';

export default {
  async fetchEmployees(){
    return await axios.get('/api/employee').then((response) => response.data)
  },
  async addEmployee(param){
    return await axios.post('/api/employee', param).then((response) => response.data)
  },
  async addBankInfo(param){
    return await axios.post('/api/bank_info', param).then((response) => response.data)
  },
  async updateBankInfo(param){
    return await axios.put('/api/bank_info/' + param.id, param).then((response) => response.data)
  },
  async updateRow(param){
    return await axios.post('/api/employee/update_row', param).then((response) => response.data)
  },
  async updateEmergencyContact(param){
    return await axios.post('/api/employee/update_emergency_contact', param).then((response) => response.data)
  },
  async updateMedical(param){
    return await axios.post('/api/employee/update_medical', param).then((response) => response.data)
  },
  async updateAddress(param){
    return await axios.post('/api/employee/update_address', param).then((response) => response.data)
  },
  async fetch_approvers(param){
    return await axios.post('/api/employee/fetch_approvers', param).then((response) => response.data)
  },
  async fetch_employee_by_name(param){
    return await axios.post('/api/employee/fetch_employee_by_name', param).then((response) => response.data)
  }
};
