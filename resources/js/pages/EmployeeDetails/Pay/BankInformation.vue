<!-- 

space
 -->
<script setup>
import { useEmployeeStore } from "@/store/employeeStore";
import AddNewBankDrawer from "./AddNewBankDrawer.vue";
//import { toast } from 'vue3-toastify';
const employeeStore = useEmployeeStore();
const isAddNewBankDrawerVisible = ref(false)
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
          <b>Bank Information</b>
        </span>
        <span class="cursor-pointer" @click="edit_fields = !edit_fields">
          <VBtn
            variant="outlined"
            color="secondary"
            @click="isAddNewBankDrawerVisible = true"
          >
            Add Bank
          </VBtn>
        </span>
      </p>
    </VCardText>
    <AddNewBankDrawer
      v-model:isDrawerOpen="isAddNewBankDrawerVisible"
      @user-data="addNewUser"
    />
  </VCard>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 16px;
}
</style>
