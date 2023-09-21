import axios from 'axios';
// config
import { api } from '../config';

// ----------------------------------------------------------------------

const axiosContent = axios.create({ baseURL:  api.basepath.main });
axiosContent.interceptors.response.use(
  (response:any) => response,
  (error:any) => Promise.reject((error.response && error.response.data) || 'Something went wrong')
);

export const axiosInstanceUam = axios.create({ baseURL: api.basepath.uam });

axiosInstanceUam.interceptors.response.use(
  (response:any) => response.data,
  (error:any) => Promise.reject((error.response && error.response.data) || 'Something went wrong')
);

export default axiosContent;
