<script setup>

import { useEmployeeStore } from "@/store/employeeStore";
import { useLeaveStore } from "@/store/leaveStore";
//import { toast } from 'vue3-toastify';
const employeeStore = useEmployeeStore();
const leaveStore = useLeaveStore();

const props = defineProps({
  data: {
    type: null,
    required: true,
  },
})
const leaves = ref([])
const profileTabData = ref()
console.log("profileTabData.value",profileTabData.value);

onMounted( async() => {
  
  profileTabData.value = employeeStore.data.employee_selected
  leaves.value = await leaveStore.fetchLeave(profileTabData.value.id)
})

</script>

<template>
  <VCard class="mb-4">
    <VCardText>
      <p class="text-lg d-flex justify-space-between">
        <span>
          <b>Current Request Leave</b>
        </span>
        <span class="cursor-pointer" @click="edit_fields = !edit_fields">
          <VIcon
            icon="tabler-pencil"
            size="25"
            class="me-2"
          />
        </span>
      </p>
      <VTable
        density="compact"
        class="text-no-wrap"
      >
        <thead>
          <tr>
            <th>
              Date Filed
            </th>
            <th>
              Date From
            </th>
            <th>
              Date To
            </th>
            <th>
              Leave Type
            </th>
            <th>
              Total Hours
            </th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="item in leaves"
            :key="item.id"
          >
            <td>
              {{ item.date_filed }}
            </td>
            <td>
              {{ item.date_from }}
            </td>
            <td>
              {{ item.date_to }}
            </td>
            <td>
              {{ item.leave_type.name }}
            </td>
            <td>
              {{ item.total_hours }}
            </td>
          </tr>
        </tbody>
      </VTable>
    </VCardText>
  </VCard>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 16px;
}
</style>
