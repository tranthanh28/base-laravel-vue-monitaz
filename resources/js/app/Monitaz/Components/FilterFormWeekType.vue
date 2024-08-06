<template>
  <el-form :model="formFilter" class="demo-form-inline">
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
    <el-form-item label="Select Month">
      <button type="button"
              class="btn btn-primary btn-with-shadow"
              data-toggle="modal"
              @click="addMonthFilter">
        {{ $t('Add Month') }}
      </button>
    </el-form-item>
    <el-form-item>
      <div v-for="(filter, index) in formFilter.month_filters" :key="index" class="month-filter">
        <el-date-picker
            v-model="formFilter.month_filters[index]"
            type="month"
            align="right"
            value-format="yyyy-MM"
        >
        </el-date-picker>
        <el-button @click="removeMonthFilter(index)" type="danger" size="small">Remove</el-button>
      </div>
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
          month_filters : ['']
        }
      }
    },
    brands: {
      type: Array
    }
  },
  data() {
    return {
      maxMonth: 12
    }
  },
  methods: {
    onSubmit() {
      this.$emit('filter');
    },
    addMonthFilter() {
      if (this.formFilter.month_filters.length >= this.maxMonth) {
        this.$notify.error({
          title: 'Error',
          message: `Số tháng thêm không được vượt quá ${this.maxMonth}`
        });
        return 0
      }
      this.formFilter.month_filters.push('');
    },
    removeMonthFilter(index) {
      this.formFilter.month_filters.splice(index, 1);
    }
  }
}
</script>
