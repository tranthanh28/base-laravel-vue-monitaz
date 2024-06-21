<template>
<WeekTypeComponent :title="title" :url-api="urlApi"></WeekTypeComponent>
</template>

<script>
import WeekTypeComponent from "@/app/Monitaz/Components/WeekTypeComponent";
export default {
  components: {WeekTypeComponent},
  data() {
    return {
      title: "Báo cáo tuần TNS ",
      urlApi: "/api/tns/week"
    }
  }
}
</script>
