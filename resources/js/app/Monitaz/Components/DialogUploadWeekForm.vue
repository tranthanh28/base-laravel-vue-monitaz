<template>
  <div>
    <el-form ref="ruleForm">
      <el-form-item label="Loại báo cáo" :label-width="formLabelWidth">
        <el-select v-model="formDataTNS.report_type" placeholder="please select type report">
          <el-option label="Kiểu Tuần" value="weekly"></el-option>
          <el-option label="Kiểu Tháng" value="monthly"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Report target" :label-width="formLabelWidth">
        <el-input v-model="formDataTNS.report_target"></el-input>
      </el-form-item>
      <el-form-item label="File" :label-width="formLabelWidth" style="margin-top: 45px">
        <el-upload
            class="upload-demo"
            action=""
            ref="upload"
            multiple
            :limit="40"
            :file-list="fileList"
            :on-exceed="handleExceed"
            :on-change="handleChange"
            :auto-upload="false"
        >
          <el-button size="small" type="primary">Click to upload</el-button>
        </el-upload>
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer d-flex justify-content-center" style="margin-top: 60px;">
                <el-button type="primary" @click="submit()">Submit</el-button>
            </span>
  </div>
</template>
<script>
export default {
  name: 'Dialog-Upload-Week-Form-1',
  props: {
    urlApi: {
      type: String,
      require: true
    }
  },
  data() {
    return {
      formDataTNS: {
        "report_type": "weekly",
        "report_target": ""
      },
      fileList: [],
      formLabelWidth: '120px',
      rules: {
        name: [
          {required: true, message: 'Please input name', trigger: 'blur'},
        ],
        pass_day: [
          {required: true, message: 'Please input pass day', trigger: 'blur'},
        ],
      }
    }
  },
  methods: {
    handleChange(file, fileList) {
      this.fileList = fileList;
    },
    handleExceed(files, fileList) {
      this.$message.warning(`Số lượng file đã đến giới hạn.`);
    },
    submitUpload() {
      this.$refs.upload.submit();
    },
    handleAdd() {
      this.startLoading()
      const formData = new FormData();
      this.fileList.forEach((file) => {
        console.log(file.name)
        formData.append('file_list[]', file.raw);
      });
      formData.append('report_type', this.formDataTNS.report_type);
      formData.append('report_target', this.formDataTNS.report_target);


      axios.post(`${this.urlApi}`, formData).then((response) => {
        this.stopLoading()
      }).catch((error) => {
        this.stopLoading()
        this.$notify.error({
          title: 'Error',
          message: 'failed'
        });
        this.errors = error.response.data.errors;
      })
    },
    submit() {
      // this.$refs.upload.clearFiles();
      this.handleAdd()
      this.$emit('submit')
    }
  }

}
</script>
<style>
#comment {
  position: absolute;
  bottom: -44px;
  color: red;
  font-style: italic;
}
.upload-demo {
  width: 360px;
}
.el-upload-list__item-name [class^=el-icon] {
  height: 25px !important;
  margin-right: 7px;
  color: #909399;
  line-height: inherit;
}
</style>
