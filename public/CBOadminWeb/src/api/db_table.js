import axios from 'axios';

/**
 * 获取数据库表
 * @returns {*}
 */
export const getTableList = () => {
  return axios.get('DbTable/getTableList')
}

/**
 * 获取数据表所有字段
 * @returns {*}
 */
export const getTableFullFields = (tableName) => {
    return axios.get('DbTable/getTableFullFields',{
        params: {
            'tableName': tableName
        }
    })
}

/**
 * 一键生成
 * @param tableName
 * @param baseConfig
 * @param searchData
 * @param searchComponentsData
 * @param columnsData
 * @param columnsComponentsData
 * @param itemData
 * @param itemShowData
 * @param itemComponentsData
 * @param fileList
 * @returns {AxiosPromise<any>}
 */
export const oneTouch = (
    tableName,
    baseConfig,
    searchData,
    searchComponentsData,
    columnsData,
    columnsComponentsData,
    itemData,
    itemShowData,
    itemComponentsData,
    fileList) => {
    searchData = searchData.filter((item) => {return item.key !== ''})
    searchComponentsData = searchComponentsData.filter((item) => {return item.key !== ''})
    columnsData = columnsData.filter((item) => {return item.key !== ''})
    columnsComponentsData = columnsComponentsData.filter((item) => {return item.key !== ''})
    itemData = itemData.filter((item) => {return item.key !== ''})
    itemShowData = itemShowData.filter((item) => {return item.key !== ''})
    return axios.post('DbTable/index',{
        tableName: tableName,
        baseConfig: baseConfig,
        searchData: searchData,
        searchComponentsData: searchComponentsData,
        columnsData: columnsData,
        columnsComponentsData: columnsComponentsData,
        itemData: itemData,
        itemShowData: itemShowData,
        itemComponentsData: itemComponentsData,
        fileList: fileList
    })
}
