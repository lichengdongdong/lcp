import axios from 'axios';
import qs from 'qs';
import global from '@/config/global'


axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8'

var base = 'http://' + global.domain + "/api";


//用户 user
export const userLoginApi = params => { return axios.post(`${base}/user/login`, qs.stringify(params)).then(res => res.data); };
export const userFindApi = params => { return axios.get(`${base}/user/find?key=id`, { params: params }); };
export const userGetApi = id => { return axios.get(`${base}/user/get/` + id); };
export const userSaveApi = params => { return axios.post(`${base}/user/save`, qs.stringify(params)); };

//职位 job
export const jobFindApi = params => { return axios.get(`${base}/job/find`, { params: params }); };
export const jobGetApi = id => { return axios.get(`${base}/job/get/` + id); };
export const jobSaveApi = params => { return axios.post(`${base}/job/save`, qs.stringify(params)); };

//简历 resume
export const resumeFindApi = params => { return axios.get(`${base}/resume/find`, { params: params }); };
export const resumeUserCntApi = params => { return axios.get(`${base}/resume/usercnt`, { params: params }); };
export const resumeGetApi = id => { return axios.get(`${base}/resume/get/` + id); };
export const resumeSaveApi = params => { return axios.post(`${base}/resume/save`, qs.stringify(params)); };
export const resumeStatusApi = (params, id) => { return axios.post(`${base}/resume/status/` + id, qs.stringify(params)); };
export const resumeBatchStatusApi = (params) => { return axios.post(`${base}/resume/batchstatus`, qs.stringify(params)); };




//resumeinterview 面试
export const interviewFindApi = (params, id) => { return axios.get(`${base}/interview/find?`, { params: params }); };
export const interviewGetApi = id => { return axios.get(`${base}/interview/get/` + id); };
export const interviewSaveApi = (params, id) => { return axios.post(`${base}/interview/save?resume_id=` + id, qs.stringify(params)); };

//interviewSkd 面试安排
export const interviewSkdFindApi = (params) => { return axios.get(`${base}/interviewskd/find?`, { params: params }); };
export const interviewSkdGetApi = id => { return axios.get(`${base}/interviewskd/get/` + id); };
export const interviewSkdSaveApi = (params, id) => { return axios.post(`${base}/interviewskd/save?id=` + id, qs.stringify(params)); };



//getcode
export const codeTplFindApi = params => { return axios.get(`${base}/coderobot/tplfind`, { params: params }); };
export const codeTplGetApi = id => { return axios.get(`${base}/coderobot/tplget/` + id); };
export const codeTplSaveApi = params => { return axios.post(`${base}/coderobot/tplsave`, qs.stringify(params)); };

export const codeDataFindApi = params => { return axios.get(`${base}/coderobot/datafind`, { params: params }); };
export const codeDataGetApi = id => { return axios.get(`${base}/coderobot/dataget/` + id); };
export const codeDataSaveApi = params => { return axios.post(`${base}/coderobot/datasave`, qs.stringify(params)); };


export const codeGetCodeApi = (tpl_id, data_id) => { return axios.get(`${base}/coderobot/getcode`, { params: { tpl_id: tpl_id, data_id: data_id } }); };
export const codeGetDBFieldsApi = params => { return axios.post(`${base}/coderobot/getdbfields`, qs.stringify(params)); };




