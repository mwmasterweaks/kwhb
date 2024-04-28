import axios from '@axios'

export const fetchDivisions = () => {
  return axios.get('/api/division').then((reponse) => reponse.data)
}
