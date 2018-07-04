
import { userFindApi } from "@/api/api";


let config={
  source_sub: {
    '51job': '前程无忧',
    'zhaopin': '智联招聘'
  },
  citys: {
    '北京': '北京',
    '上海': '上海',
    '深圳': '深圳',
    '西安': '西安',
    '杭州': '杭州'
  },

  industrys: [
    {
      value: '互联网',
      label: '互联网'
    }, {
      value: '建筑',
      label: '建筑'
    }
  ],

  resume_status: {
    '0': "待处理",
    '1': "待定",
    '2': "面试中", 
    '-1': "不合适"
  },

  resume_source: [

    { value: '0', label: "未选择" },
    { value: '1', label: "招聘网站" },
    { value: '2', label: "官网投递" }
  ],
  interview_status: {
    '0': '进行中',
    '-2': '未参加',
    '1': '通过',
    '-1': '未通过'
  },
  interview_skd_status: {
    '0': '进行中',
    '-2': '未参加',
    '1': '面试通过',
    '-1': '面试不通过'
  },
  admins:{},
  sex: {
    '男': '男',
    '女': '女',
  },
  workyear_rang: {
    '-1': '应届生',
    '1-3': '1-3年',
    '3-5': '3-5年',
    '5-': '5年以上',
  },
  //
  resume_edus: {
    '1': "高中",
    '2': "大专",
    '3': "专科",
    '4': "本科",
    '5': "硕士",
    '6': "博士",
  },
  resume_apply_status: {
    '1': "观望",
    '2': "不找工作",
    '3': "寻找工作中"
  },
  resume_source_sub: {
    '51job': '前程无忧',
    'zhaopin': "智联招聘",
    'liepin': "猎聘",
    'zhipin': "Boss直聘",
    'lagou': "拉勾",
  },
  job_status: {
    '0': "正常",
    '-1': "关闭",
    '-2': "下线"
  },
  resume_status_sub: {
    '-1': [
      '学历不符',
      '薪资不符'
    ],
    '1': [
      '意向人才'
    ]
  }
};
export default config; 