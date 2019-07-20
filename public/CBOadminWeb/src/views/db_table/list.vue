<template>
  <div>
    <!--表格-->
    <Row>
      <Col span="24">
        <Card>
          <div>
            <Table :loading="tableLoading" :columns="columnsList" :data="tableData" border disabled-hover></Table>
          </div>
          <div class="margin-top-15" style="text-align: center">
          </div>
        </Card>
      </Col>
    </Row>
  </div>
</template>

<script>
  import {getTableList} from '@/api/db_table'

  // 配置
  const configButton = (vm, h, currentRow, index) => {
    return h('Button', {
      props: {
        type: 'primary'
      },
      style: {
        margin: '5px'
      },
      on: {
        'click': () => {
          vm.$router.push({
            name: 'db_table_details',
            params: { tableName: currentRow.Name }
          });
        }
      }
    }, '配置');
  };

  export default {
    name: "list",
    components: {
    },
    data() {
      return {
        // 初始化表格
        columnsList: [
          {
            type: 'index',
            title: '序号',
            align: 'center',
            width: 65
          },
          {
            title: '表名',
            key: 'Name'
          },
          {
            title: '注释',
            key: 'Comment'
          },
          {
            title: '类型',
            align: 'center',
            key: 'Engine',
            width: 100
          },
          {
            title: '创建时间',
            align: 'center',
            key: 'Create_time',
            width: 180
          },
          {
            title: '操作',
            align: 'center',
            key: 'handle',
            width: 100,
            handle: ['config']
          }
        ],
        // 表格加载状态
        tableLoading: true,
        // 表格数据
        tableData: []
      }
    },
    created() {
      this.init()
      this.getList()
    },
    methods: {
      // 初始化页面
      init() {
        let vm = this
        this.columnsList.forEach(item => {
          // 操作列
          if (item.handle) {
            item.render = (h, param) => {
              let currentRowData = vm.tableData[param.index]
              return h('div', [
                configButton(vm, h, currentRowData, param.index)
              ]);
            };
          }
        })
      },
      // 获取列表数据
      getList() {
        this.tableLoading = true
          getTableList().then(res => {
              this.tableLoading = false
              this.tableData = res.data.data
          })
      }
    }
  }
</script>

<style scoped>

</style>
