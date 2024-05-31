import axios from '@axios';

export default {
  async fetchEmployees(){
    return await axios.get('/api/myob/contact/employee').then((response) => response.data)
  },
  async fetchEmployeeByDisplayID(){
    return await axios.get('/api/myob/fetch_employee_by_display_id/EMP000002').then((response) => response.data)
  }
};
