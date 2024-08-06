<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-sm-12 col-md-6">
        <app-breadcrumb :page-title="title" :directory="$t('datatables')" :icon="'grid'"/>
      </div>
    </div>
    <FilterForm :formFilter="formFilter" :brands="brands" @filter="exportExcel"></FilterForm>
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
        month_filters: [""]
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
    exportExcel() {
      this.startLoading()
      let data = this.formFilter
      axios.post(`${this.urlApi}/export-excel`, data).then((response) => {
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
    }
  }
}
</script>
