<template>
<ExportExcelComponent :title="title" :url-api="urlApi"></ExportExcelComponent>
</template>

<script>
import ExportExcelComponent from "@/app/Monitaz/Components/ExportExcelComponent";
export default {
  components: {ExportExcelComponent},
  data() {
    return {
      title: "Xuất báo cáo top costs ",
      urlApi: "/api/tns/report"
    }
  }
}
</script>
