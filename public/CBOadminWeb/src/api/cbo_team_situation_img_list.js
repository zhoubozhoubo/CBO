import axios from 'axios';

/**
 * 获取列表
 * @param pageData
 */
export const getDataList = (pageData, searchConf) => {
    return axios.get('TeamSituationImg/getList',{
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
    return axios.post('TeamSituationImg/coruData', data)
}

export const getCompetitionSeason = () => {
    return axios.get('TeamSituationImg/getCompetitionSeason')
}

export const getSeasonStage = (seasonId) => {
    return axios.get('TeamSituationImg/getSeasonStage',{
        params: {
            'seasonId': seasonId
        }
    })
}

export const getArea = (seasonId,stageId) => {
    return axios.get('TeamSituationImg/getArea',{
        params: {
            'seasonId': seasonId,
            'stageId': stageId
        }
    })
}
