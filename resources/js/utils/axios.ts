import axios from 'axios';
// config
import { api,service } from '../config';

// ----------------------------------------------------------------------

const axiosInstance = axios.create({ baseURL:  api.basepath.main });
axiosInstance.interceptors.response.use(
  (response) => response,
  (error) => Promise.reject((error.response && error.response.data) || 'Something went wrong')
);

export const axiosContent = axios.create({ baseURL: service.cms.basepath });
export const axiosInstanceProxy = axios.create({ baseURL: service.proxy.basepath });
export const axiosInstanceUam = axios.create({ baseURL: api.basepath.uam });
axiosContent.interceptors.response.use(
  (response) => response.data,
  (error) => Promise.reject((error.response && error.response.data) || 'Something went wrong')
);

export default axiosInstance;
