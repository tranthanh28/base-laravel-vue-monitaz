<template>
  <el-form :inline="true" :model="formFilter" class="demo-form-inline">
    <el-form-item label="Brand">
      <el-select v-model="formFilter.brand_type" placeholder="Select">
        <el-option
            v-for="brand in brands"
            :key="brand"
            :label="brand"
            :value="brand">
        </el-option>
      </el-select>
    </el-form-item>
    <el-form-item>
        <el-date-picker
            v-model="formFilter.range_date "
            type="daterange"
            align="right"
            start-placeholder="Start Date"
            end-placeholder="End Date"
            value-format="yyyy-MM-dd"
        >
        </el-date-picker>
    </el-form-item>
    <el-form-item>
      <el-button type="primary" @click="onSubmit">Export Excel</el-button>
    </el-form-item>
  </el-form>
</template>
<script>
export default {
  props:{
    formFilter: {
      type: Object,
      default() {
        return {
          brand_type: '',
          range_date : ''
        }
      }
    },
    brands: {
      type: Array
    }
  },
  data() {
    return {
    }
  },
  methods: {
    onSubmit() {
      this.$emit('filter');
    }
  }
}
</script>
