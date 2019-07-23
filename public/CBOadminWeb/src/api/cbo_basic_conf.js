import axios from 'axios';

/**
 * 获取列表
 * @param basicConfName
 */
export const getDataDetails = (basicConfName) => {
    return axios.get('BasicConf/getDetails',{
        params: {
            'name': basicConfName
        }
    })
}

/**
 * 新增/更新数据
 */
export const coruData = (data) => {
    return axios.post('BasicConf/coruData', data)
}

