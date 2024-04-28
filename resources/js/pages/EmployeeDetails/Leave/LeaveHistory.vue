<script setup>

import { useEmployeeStore } from "@/store/employeeStore";
//import { toast } from 'vue3-toastify';
const employeeStore = useEmployeeStore();

const props = defineProps({
  data: {
    type: null,
    required: true,
  },
})

</script>

<template>
  <VCard class="mb-4">
    <VCardText>
      <p class="text-lg d-flex justify-space-between">
        <span>
          <b>Leave History</b>
        </span>
        <span class="cursor-pointer" @click="edit_fields = !edit_fields">
          <VIcon
            icon="tabler-pencil"
            size="25"
            class="me-2"
          />
        </span>
      </p>
    </VCardText>
  </VCard>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 16px;
}
</style>
