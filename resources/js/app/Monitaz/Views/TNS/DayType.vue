<template>
<DayTypeComponent :title="title" :url-api="urlApi"></DayTypeComponent>
</template>

<script>
import DayTypeComponent from "@/app/Monitaz/Components/DayTypeComponent";
export default {
  components: {DayTypeComponent},
  data() {
    return {
      title: "Báo cáo ngày TNS",
      urlApi: "/api/tns/day"
    }
  }
}
</script>
