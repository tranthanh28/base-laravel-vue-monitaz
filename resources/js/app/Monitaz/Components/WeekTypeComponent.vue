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


    <el-dialog :title="titleDialog" :visible.sync="dialogFormVisible">
      <DialogUploadWeekForm :urlApi="urlApi" @submit="submit"></DialogUploadWeekForm>
    </el-dialog>
  </div>
</template>

<script>
import DialogUploadWeekForm from "@/app/Monitaz/Components/DialogUploadWeekForm";
import FilterForm from "@/app/Monitaz/Components/FilterForm";
import StringMethod from "@/core/helpers/string/StringMethod";

export default {
  components: {DialogUploadWeekForm, FilterForm},
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
      formList: [],
      formLabelWidth: '120px',
      errors: "",
      data: [],
    }
  },
  created() {
    // let urlParams = new URLSearchParams(window.location.search);
    // if (urlParams.has('page')) {
    //   this.page = urlParams.get('page');
    // }
    // if (urlParams.has('search')) {
    //   this.formFilter.search = urlParams.get('search');
    // }
    //
    // this.filterSearchForm(this.page)
  },
  methods: {
    handleCreate() {
      this.titleDialog = 'Create'
      this.dialogFormVisible = true
      this.formList = []
    },
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
      this.handleAdd()
    },
    handleAdd() {
        this.dialogFormVisible = false
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
