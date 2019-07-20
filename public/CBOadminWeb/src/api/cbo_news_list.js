import axios from 'axios';

/**
 * 获取列表
 * @param pageData
 */
export const getDataList = (pageData, searchConf) => {
    return axios.get('News/getList',{
        params: {
            'page':
            pageData.currentPage,
            'size':
            pageData.pageSize,
            'searchConf':
            searchConf,
        }
    })
}

/**
 * 新增/更新数据
 */
export const coruData = (data) => {
    return axios.post('News/coruData', data)
}

