import axios from 'axios';
import qs from 'qs';
import global from '@/global'

axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8'

var base = 'http://' + global.domain + "/api";


//用户 user
export const userLoginApi = params => { return axios.post(`${base}/user/login`, qs.stringify(params)).then(res => res.data); };



 