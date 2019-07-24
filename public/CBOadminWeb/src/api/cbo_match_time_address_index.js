import axios from 'axios';

/**
 * 获取列表
 * @param pageData
 */
export const getDataList = (pageData, searchConf) => {
    return axios.get('MatchTimeAddress/getList',{
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
 * 获取省数据列表
 * @returns {AxiosPromise<any>}
 */
export const getProvinceList = () => {
    return axios.get('Index/getProvinceList')
}

/**
 * 新增/更新数据
 */
export const coruData = (data) => {
    return axios.post('MatchTimeAddress/coruData', data)
}

