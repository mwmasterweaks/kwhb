import axios from '@axios'

export const fetchLocations = () => {
  return axios.get('/api/location').then((reponse) => reponse.data)
}
