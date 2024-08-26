<template>
  <el-form :model="formFilter" class="demo-form-inline">
    <el-form-item label="Report api" :label-width="formLabelWidth">
      <el-select v-model="formFilter.api_name" placeholder="Select">
        <el-option
            v-for="api_name in list_api"
            :key="api_name"
            :label="api_name"
            :value="api_name">
        </el-option>
      </el-select>
    </el-form-item>
    <el-form-item label="Month" :label-width="formLabelWidth">
      <el-date-picker
          v-model="formFilter.month_filter"
          type="month"
          value-format="yyyy-MM"
          placeholder="Select month">
      </el-date-picker>
    </el-form-item>
    <el-form-item>
      <span class="d-flex justify-content-center" style="margin-top: 60px;">
      <el-button type="primary" @click="onSubmit">Export Excel</el-button>
      </span>
    </el-form-item>
  </el-form>
</template>
<script>
export default {
  props: {
    formFilter: {
      type: Object,
      default() {
        return {
          api_name: '',
          month_filter: ''
        }
      }
    },
    list_api: {
      type: Array
    }
  },
  data() {
    return {
      formLabelWidth: '120px',
      maxMonth: 12
    }
  },
  methods: {
    onSubmit() {
      this.$emit('filter');
    },
  }
}
</script>
