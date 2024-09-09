<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-sm-12 col-md-6">
        <app-breadcrumb :page-title="title" :directory="$t('datatables')" :icon="'grid'"/>
      </div>
    </div>
    <FilterForm :formFilter="formFilter" :list_api="list_api" @filter="exportExcel"></FilterForm>
  </div>
</template>

<script>
import FilterForm from "@/app/Monitaz/Components/FormExportExcelCustomComponent";

export default {
  components: {FilterForm},
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
        month_filters: [""]
      },
      list_api: [],
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
    this.getDataSelect()
  },
  methods: {
    getDataSelect() {
      this.startLoading()
      axios.get(`${this.urlApi}/get-api-select`).then((response) => {
        this.stopLoading()
        this.list_api = response.data.data
      }).catch((errors) => {
        this.stopLoading()
        this.handleErrorNotPermission(errors)
      })
    },
    exportExcel() {
      this.startLoading()
      let data = this.formFilter
      axios.post(`${this.urlApi}/export-excel-custom`, data).then((response) => {
        if (response?.data?.data?.code == 1) {
          let data = response.data.data
          const base64String = data.data
          let fileName = data.file_name ?? "file.xlsx"
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
          link.download = fileName; // Tên tệp khi tải xuống
          document.body.appendChild(link);
          link.click();

          // Dọn dẹp
          document.body.removeChild(link);
          URL.revokeObjectURL(url);
        } else {
          this.$notify.error({
            title: 'Error',
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
    }
  }
}
</script>
