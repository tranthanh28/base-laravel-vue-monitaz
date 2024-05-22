<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-sm-12 col-md-6">
        <app-breadcrumb :page-title="title" :directory="$t('datatables')" :icon="'grid'"/>
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
          <a v-if="scope.row.file_path" :href="urlGenerator(`/storage/reports/${scope.row.file_path}`)" download>
            <el-button
                size="mini">
              <i class="el-icon-download"></i>
            </el-button>
          </a>
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
      <DialogUploadForm :form="form" @submit="submit"></DialogUploadForm>
    </el-dialog>
  </div>
</template>

<script>
import DialogUploadForm from "@/app/Monitaz/Components/DialogUploadForm";
import FilterForm from "@/app/Monitaz/Components/FilterForm";
import StringMethod from "@/core/helpers/string/StringMethod";

export default {
  components: {DialogUploadForm, FilterForm},
  props: {
    title: {
      type: String,
      require: true
    },
    urlApi: {
      type: String,
      require: true
    }
  },
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
      page: 1,
      totalPages: 0,
      perPage: 10,
      currentPage: 1,

      search: '',
      dialogTableVisible: false,
      dialogFormVisible: false,
      form: {
        id: "",
        name: '',
        pass_day: '',
        content_file: "",
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
    if (urlParams.has('search')) {
      this.formFilter.search = urlParams.get('search');
    }

    this.filterSearchForm(this.page)
  },
  methods: {
    filterSearchForm(page) {
      if (page == null) {
        this.page = 1
      }
      this.page = 1
      let search = this.formFilter.search;
      this.formWindowUrl.search = search;
      this.formWindowUrl.page = this.page
      let pageTitle = document.title,
          query = StringMethod.objectToQueryString(this.formWindowUrl);
      window.history.pushState("", pageTitle, `?${query}`);
      this.getList(this.page, search)
    },
    handleCurrentChange(val) {
      this.formWindowUrl.page = val
      let pageTitle = document.title,
          query = StringMethod.objectToQueryString(this.formWindowUrl);
      window.history.pushState("", pageTitle, `?${query}`);
      this.getList(val)
    },
    filterTag(value, row) {
      return row.status == value;
    },
    submit() {
      this.startLoading()
      if (this.form.id) {
        this.handleUpdate()
      } else {
        this.handleAdd()
      }
    },
    handleEdit(row) {
      if (row.status == 1) return
      this.titleDialog = 'Edit'
      this.dialogFormVisible = true
      this.form.id = row.id
      this.form.name = row.name
      this.form.pass_day = row.pass_day
      this.form.content_file = ""
    },
    handleCreate() {
      this.titleDialog = 'Create'
      this.dialogFormVisible = true
      this.form.id = null
      this.form.name = ''
      this.form.pass_day = 60
      this.form.content_file = ""
    },
    handleAdd() {
      const formData = new FormData();
      formData.append("file", this.form.content_file);
      formData.append("name", this.form.name);

      axios.post(`${this.urlApi}`, formData).then((response) => {
        this.stopLoading()
        this.getList()
        this.dialogFormVisible = false
        this.form = {
          name: '',
          content_file: "",
          pass_day: 60,
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
    handleUpdate() {
      axios.put(`${this.urlApi}/${this.form.id}`, this.form).then((response) => {
        this.stopLoading()
        this.getList()
        this.dialogFormVisible = false
        this.form = {
          id: '',
          name: '',
          pass_day: '',
          content_file: "",
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
      axios.post(`${this.urlApi}/export-excel`, dataDowload, {
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
    getList(page = 1, search = '') {
      this.startLoading()
      if (search) {
        search = `&search=${search}`
      }
      axios.get(`${this.urlApi}?page=${page}${search}`).then((response) => {
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
