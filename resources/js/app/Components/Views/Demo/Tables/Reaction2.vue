<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-sm-12 col-md-6">
        <app-breadcrumb page-title="Reaction" :directory="$t('datatables')" :icon="'grid'"/>
      </div>
      <div class="col-sm-12 col-md-6 breadcrumb-side-button">
        <div class="float-md-right mb-3 mb-sm-3 mb-md-0">
          <button type="button"
                  class="btn btn-primary btn-with-shadow"
                  data-toggle="modal"
                  @click="handleCreate">
            {{ $t('add') }}
          </button>
        </div>
      </div>
    </div>
    <FilterForm :formFilter="formFilter" @filter="filterSearchForm"></FilterForm>

    <el-table
        :data="data.filter(data => !search || data.name.toLowerCase().includes(search.toLowerCase()))"
        style="width: 100%">
      <el-table-column
          label="name"
          prop="name">
      </el-table-column>
      <el-table-column
          label="status"
          prop="status"
          :filters="[{ text: 'Pending', value: '0' }, { text: 'Process', value: '1' }, { text: 'Done', value: '2' }]"
          :filter-method="filterTag"
          filter-placement="bottom-end">
        <template slot-scope="scope">
          <el-tag v-if="scope.row.status == 2" type="success">Done</el-tag>
          <el-tag v-if="scope.row.status == 1" type="info">Process</el-tag>
          <el-tag v-if="scope.row.status == 0" type="warning">Pending</el-tag>
        </template>
      </el-table-column>
      <el-table-column
          label="created"
          prop="created_at">
      </el-table-column>
      <el-table-column
          align="right">
        <template slot="header" slot-scope="scope">
          <el-input
              v-model="search"
              size="mini"
              placeholder="Type to search"/>
        </template>
        <template slot-scope="scope">
          <el-button
              size="mini"
              :disabled="scope.row.status == 1"
              @click="handleEdit(scope.row)"><i class="el-icon-edit"></i>Edit
          </el-button>
          <el-button
              v-if="scope.row.status == 2"
              size="mini">
          <i class="el-icon-download">
          </i>
            <a :href="getExcel(scope.row)" download>Download</a>
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-pagination
        background
        layout="prev, pager, next"
        @current-change="handleCurrentChange"
        :current-page.sync="currentPage"
        :page-size="perPage"
        :total="totalPages">
    </el-pagination>

    <el-dialog :title="titleDialog" :visible.sync="dialogFormVisible">
      <DialogReactionForm :form="form" @submit="submit"></DialogReactionForm>
    </el-dialog>
  </div>
</template>

<script>
import DialogReactionForm from "@/app/Monitaz/Components/DialogReactionForm";
import FilterForm from "@/app/Monitaz/Components/FilterForm";
import StringMethod from "@/core/helpers/string/StringMethod";
import {
  STORE_EXCEL
}  from '@/app/Config/ApiUrl';

export default {
  components: {DialogReactionForm, FilterForm},
  data() {
    return {
      formFilter: {
        search: '',
        status: []
      },
      formWindowUrl: {
        search: '',
        status: '',
        page: ''
      },
      titleDialog: "Create",
      //Pagination
      page:1,
      totalPages: 0,
      perPage: 10,
      currentPage: 1,

      search: '',
      dialogTableVisible: false,
      dialogFormVisible: false,
      form: {
        id: "",
        name: '',
        post_ids: "",
      },
      formLabelWidth: '120px',
      errors: "",
      data: [],
    }
  },
  created() {
    let urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('page')) {
      this.page = urlParams.get('page');
    }

    if (urlParams.has('status')) {
      this.formFilter.status = urlParams.get('status').split(",");
    }
    if (urlParams.has('search')) {
      this.formFilter.search = urlParams.get('search');
    }

    this.filterSearchForm(this.page)
  },
  methods: {
    getExcel(row)
    {
      return STORE_EXCEL + 'report_update_reaction_v2/'+ row.file_name
    },
    filterSearchForm(page = null) {
      if (page == null) {
        this.page = 1
      }
      let status = this.formFilter.status.join(",");
      let search = this.formFilter.search;
      this.formWindowUrl.search = search;
      this.formWindowUrl.status = status;
      this.formWindowUrl.page = this.page
      let pageTitle = document.title,
          query = StringMethod.objectToQueryString(this.formWindowUrl);
      window.history.pushState("", pageTitle, `?${query}`);
      this.getListReactions(this.page, status, search)
    },
    handleCurrentChange(val) {
      this.formWindowUrl.page = val
      let pageTitle = document.title,
          query = StringMethod.objectToQueryString(this.formWindowUrl);
      window.history.pushState("", pageTitle, `?${query}`);
      this.getListReactions(val)
    },
    filterTag(value, row) {
      return row.status == value;
    },
    submit() {
      this.startLoading()
      if (this.form.id) {
        this.handleUpdateReaction()
      } else {
        this.handleAddReaction()
      }
    },
    handleEdit(row) {
      if (row.status == 1) return
      this.titleDialog = 'Edit'
      this.dialogFormVisible = true
      this.form.id = row.id
      this.form.name = row.name
      this.form.post_ids = ""
    },
    handleCreate() {
      this.titleDialog = 'Create'
      this.dialogFormVisible = true
      this.form.id = null
      this.form.name = ''
      this.form.post_ids = ""
    },
    handleAddReaction() {
      axios.post('/api/reaction', this.form).then((response) => {
        this.stopLoading()
        this.getListReactions()
        this.dialogFormVisible = false
        this.form = {
          name: '',
          post_ids: "",
        }
      }).catch((error) => {
        this.stopLoading()
        this.$notify.error({
          title: 'Error',
          message: 'failed'
        });
        this.errors = error.response.data.errors;
      })
    },
    handleUpdateReaction() {
      axios.put(`/api/reaction/${this.form.id}`, this.form).then((response) => {
        this.stopLoading()
        this.getListReactions()
        this.dialogFormVisible = false
        this.form = {
          id:'',
          name: '',
          post_ids: "",
        }
      }).catch((error) => {
        this.stopLoading()
        this.$notify.error({
          title: 'Error',
          message: 'failed'
        });
        this.errors = error.response.data.errors;
      })
    },
    handleDowload(row) {
      let dataDowload = {
        file_name: row.file_name
      }
      axios.post('/api/reaction/export-excel', dataDowload, {
        responseType: 'blob'
      }).then((response) => {
        const url = URL.createObjectURL(new Blob([response.data], {
          type: 'application/vnd.ms-excel'
        }))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', dataDowload.file_name)
        document.body.appendChild(link)
        link.click()
      }).catch((error) => {
        this.stopLoading()
        this.$notify.error({
          title: 'Error',
          message: 'failed'
        });
      })
    },
    getListReactions(page = 1, status = '', search = '') {
      this.startLoading()
      if (status) {
        status = `&status=${status}`
      }
      if (search) {
        search = `&search=${search}`
      }
      axios.get(`/api/reaction?page=${page}${status}${search}`).then((response) => {
        this.stopLoading()
        this.data = response.data.data.data
        this.totalPages = response.data.data.total
        this.perPage = response.data.data.per_page
        this.currentPage = response.data.data.current_page
      }).catch((errors) => {
        this.stopLoading()
        this.handleErrorNotPermission(errors)
      })
    }
  }

}
</script>
<style scoped>
a {
  color: #606266 !important;
  text-decoration: none !important;
}
</style>
