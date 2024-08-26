<template>
<ExportExcelCustomComponent :title="title" :url-api="urlApi"></ExportExcelCustomComponent>
</template>

<script>
import ExportExcelCustomComponent from "@/app/Monitaz/Components/ExportExcelCustomComponent";
export default {
  components: {ExportExcelCustomComponent},
  data() {
    return {
      title: "Xuất báo cáo TNS ",
      urlApi: "/api/tns/report"
    }
  }
}
</script>
