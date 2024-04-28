<script setup>
import { useEmployeeStore } from "@/store/employeeStore";
import CurrentRequestLeave from './CurrentRequestLeave.vue';
import LeaveBalance from './LeaveBalance.vue';
import LeaveHistory from './LeaveHistory.vue';
import applyLeaveDialogVue from "./applyLeaveDialog.vue";


const employeeStore = useEmployeeStore();
const profileTabData = ref()
const applyLeaveVisible = ref(false)

profileTabData.value = employeeStore.data.employee_selected
</script>

<template>
<VRow v-if="profileTabData">
  <VCol md="6" cols="12">
    <VRow>
      <VCol md="12" cols="12">
        <div class="pa-2 bg-var-theme-background rounded">
          <h6 class="text-base font-weight-medium">
            <center>
              <VBtn class="btn_apply" color="kwhb" size="large" @click="applyLeaveVisible = true">Apply for Leave</VBtn>
            </center>
          </h6>
        </div>
      </VCol>
    </VRow>
    <VRow>
      <VCol md="12" cols="12">
        <LeaveBalance :data="profileTabData" />
      </VCol>
    </VRow>
  </VCol>
  <VCol md="6" cols="12">
    <VRow>
      <VCol md="12" cols="12">
        <CurrentRequestLeave :data="profileTabData" />
      </VCol>
    </VRow>
    <VRow>
      <VCol md="12" cols="12">
        <LeaveHistory :data="profileTabData" />
      </VCol>
    </VRow>
  </VCol>
  <applyLeaveDialogVue
  v-model:isDialogVisible="applyLeaveVisible"
  ></applyLeaveDialogVue>
</VRow>
</template>

<style lang="scss" scoped>
.btn_apply {
  margin: 30px;
}
</style>
