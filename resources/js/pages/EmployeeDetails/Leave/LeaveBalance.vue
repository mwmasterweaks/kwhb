<script setup>

import { useEmployeeStore } from "@/store/employeeStore";
import { useLeaveBalanceStore } from "@/store/leaveBalanceStore";
import { useLeaveTypeStore } from "@/store/leaveTypeStore";
//import { toast } from 'vue3-toastify';
const employeeStore = useEmployeeStore();
const leaveTypeStore = useLeaveTypeStore();
const leaveBalanceStore = useLeaveBalanceStore();
const profileTabData = ref()
const leave_type_selected = ref('')
const leaveBalance = ref({})

const props = defineProps({
  data: {
    type: null,
    required: true,
  },
})
profileTabData.value = employeeStore.data.employee_selected



const fetchLeaveBalance = async() => {
  var temp = {
    employee_id: profileTabData.value.id,
    leave_type_id: leave_type_selected.value
  }
  const ret = await leaveBalanceStore.fetchLeaveBalance(temp);
  if(ret != null)
    leaveBalance.value = ret
}

</script>

<template>
  <VCard class="mb-4">
    <VCardText>
      <p class="text-lg d-flex justify-space-between">
        <span>
          <b>Leave Balance</b>
        </span>
        <span class="cursor-pointer" @click="edit_fields = !edit_fields">
          <!-- <VIcon
            icon="tabler-pencil"
            size="25"
            class="me-2"
          /> -->
        </span>
      </p>
      <VRow>
         <VCol cols="12" md="6">
          <VRow>
            <VCol cols="12" md="12" >
              <AppSelect
                  v-model="leave_type_selected"
                  label="Leave Type"
                  item-title="name"
                  item-value="id"
                  :rules="[requiredValidator]"
                  :items="leaveTypeStore.data.leave_types"
                  @update:modelValue="fetchLeaveBalance()"
              />
            </VCol>
          
          </VRow>
         </VCol>
      </VRow>
      <VRow>
        <VCol cols="12" md="6">
          <VCard>
            <VCardText>
              <div class="d-flex flex-column gap-y-1">
                <span class="text-body-1 text-medium-emphasis">Leave Balance</span>
                <div>
                  <h4 class="text-h4">
                    {{ leaveBalance.balance }} hours
                  </h4>
                </div>
              </div>
            </VCardText>
          </VCard>
        </VCol>
        <VCol cols="12" md="6">
          <VCard>
            <VCardText>
              <div class="d-flex flex-column gap-y-1">
                <span class="text-body-1 text-medium-emphasis">Availed</span>
                <div>
                  <h4 class="text-h4">
                    {{ leaveBalance.availed }} hours
                  </h4>
                </div>
              </div>
            </VCardText>
          </VCard>
        </VCol>
      </VRow>
    </VCardText>
  </VCard>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 16px;
}
</style>
