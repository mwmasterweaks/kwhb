import axios from '@axios';

export default {
  async useCSRF(){
    return axios.get('/sanctum/csrf-cookie')
  },
  async useLogin(param){
    return axios.post('/api/auth/login', param).then((reponse) => reponse.data)
  },
  async useLogOut(param){
    return axios.post('/api/auth/logout', param).then((reponse) => reponse.data)
  }
};
