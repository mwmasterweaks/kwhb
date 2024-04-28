import axios from '@axios';

export default {
  async fetchEmployees(){
    return await axios.get('/api/myob/contact/employee').then((response) => response.data)
  }
};
