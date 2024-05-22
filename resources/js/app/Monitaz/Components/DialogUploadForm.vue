<template>
  <div>
    <el-form :model="form" :rules="rules" ref="ruleForm">
<!--      <el-form-item label="Name" :label-width="formLabelWidth" prop="name">-->
<!--        <el-input v-model="form.content_file" type="file" autocomplete="off"></el-input>-->
<!--      </el-form-item>-->
<!--      <el-form-item label="Pass day" :label-width="formLabelWidth" prop="pass_day">-->
<!--        <el-input v-model="form.pass_day" autocomplete="off"></el-input>-->
<!--        <div id="comment">(*) * ngày hiện tại - pass_day => dữ liệu lấy bắt đầu từ khoảng đấy *</div>-->
<!--      </el-form-item>-->
      <el-form-item label="File" :label-width="formLabelWidth" style="margin-top: 45px">
<!--        <el-upload-->
<!--            class="upload-demo"-->
<!--            ref="upload"-->
<!--            action="http://localhost:8016/"-->
<!--            :auto-upload="false">-->
<!--          <el-button slot="trigger" size="small" type="primary">select file</el-button>-->
<!--          <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">upload to server</el-button>-->
<!--          <div class="el-upload__tip" slot="tip">jpg/png files with a size less than 500kb</div>-->
<!--        </el-upload>-->
        <el-upload
            action=""
            class="upload-demo"
            style="height: 50px;"
            ref="upload"
            :limit="1"
            :on-change="handleImport"
            :auto-upload="false">
          <el-button slot="trigger" size="small" type="primary">Select file</el-button>
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
  name: 'Dialog-Role',
  props: {
    form: {
      type: Object,
      default() {
        return {
          id: '',
          name: '',
          pass_day: '',
          post_ids: '',
        }
      }
    }
  },
  data() {
    return {
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
    submitUpload() {
      this.$refs.upload.submit();
    },
    submit() {
      this.$refs['ruleForm'].validate((valid) => {
        this.$refs.upload.clearFiles();
        if (valid) {
          this.$emit('submit')
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    handleImport(file) {
      this.uploadFile = file
      console.log(file);
      this.form.content_file = file.raw
      this.form.name = this.uploadFile.name

      // let reader = new FileReader()
      // let _this = this;
      // reader.onload = async (e) => {
      //   try {
      //     this.form.content_file = e.target.result
      //     this.form.name = _this.uploadFile.name
      //   } catch (err) {
      //     console.log(`Load JSON file error: ${err.message}`)
      //   }
      // }
      // reader.readAsBinaryString(file.raw);
    },
  }

}
</script>
<style scoped>
#comment {
  position: absolute;
  bottom: -44px;
  color: red;
  font-style: italic;
}
</style>
