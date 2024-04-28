import axios from '@axios'

export const fetchLeaveTypes = () => {
  return axios.get('/api/leave_type').then((reponse) => reponse.data)
}
