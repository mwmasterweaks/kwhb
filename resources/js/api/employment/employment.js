import axios from '@axios';


export const fetchEmployments = () => {
  return axios.get('/api/employment').then((reponse) => reponse.data)
}

export const updateRow = (data) => {
  return 0;
 // return axios.get('/api/employment').then((reponse) => reponse.data)
}

