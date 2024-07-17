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
    <FilterForm :formFilter="formFilter" :brands="brands" @filter="exportExcelWeek"></FilterForm>

    <el-table
        :data="data.filter(data => !search || data.name.toLowerCase().includes(search.toLowerCase()))"
        style="width: 100%">
      <el-table-column
          label="name"
          prop="name">
      </el-table-column>
      <el-table-column
          label="report_type"
          prop="report_type">
      </el-table-column>
      <el-table-column
          label="status"
          prop="status">
        <template slot-scope="scope">
          <el-tag v-if="scope.row.status == 2" type="danger">ERROR</el-tag>
          <el-tag v-if="scope.row.status == 1" type="success">Done</el-tag>
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
      <DialogUploadWeekForm :urlApi="urlApi" @submit="submit"></DialogUploadWeekForm>
    </el-dialog>
  </div>
</template>

<script>
import DialogUploadWeekForm from "@/app/Monitaz/Components/DialogUploadWeekForm";
import FilterForm from "@/app/Monitaz/Components/FilterFormWeekType";
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
        brand_type: "",
        range_date: ""
      },
      brands: [],
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
    this.getBrands()
    this.getList()
  },
  methods: {
    getBrands() {
      this.startLoading()
      axios.get(`${this.urlApi}/get-brand-type-list`).then((response) => {
        this.stopLoading()
        this.brands = response.data.data
      }).catch((errors) => {
        this.stopLoading()
        this.handleErrorNotPermission(errors)
      })
    },
    handleCreate() {
      this.titleDialog = 'Create'
      this.dialogFormVisible = true
      this.formList = []
    },
    exportExcelWeek() {
      this.startLoading()
      let data = this.formFilter
      axios.post(`${this.urlApi}/export-excel-week`, data).then((response) => {
        console.log("data", response)
        if (response?.data?.data?.code == 1) {
          const base64String = response.data.data.data
          const byteCharacters = atob(base64String);
          const byteArrays = [];

          for (let offset = 0; offset < byteCharacters.length; offset += 512) {
            const slice = byteCharacters.slice(offset, offset + 512);
            const byteNumbers = new Array(slice.length);

            for (let i = 0; i < slice.length; i++) {
              byteNumbers[i] = slice.charCodeAt(i);
            }

            const byteArray = new Uint8Array(byteNumbers);
            byteArrays.push(byteArray);
          }

          const blob = new Blob(byteArrays, { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });

          // Tạo URL đối tượng từ Blob
          const url = URL.createObjectURL(blob);

          // Tạo thẻ <a> để kích hoạt tải xuống
          const link = document.createElement('a');
          link.href = url;
          link.download = 'file.xlsx'; // Tên tệp khi tải xuống
          document.body.appendChild(link);
          link.click();

          // Dọn dẹp
          document.body.removeChild(link);
          URL.revokeObjectURL(url);
        } else {
          this.$notify.error({
            title: 'INFO',
            message: 'Không có dữ liệu trả về'
          });
        }

        this.stopLoading()
      }).catch((error) => {
        this.stopLoading()
        this.$notify.error({
          title: 'Error',
          message: 'failed'
        });
      })


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
        this.getList()
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
    handleDownload(row) {
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
