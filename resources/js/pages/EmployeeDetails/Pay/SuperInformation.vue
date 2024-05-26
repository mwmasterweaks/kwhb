<script setup>

import { useEmployeeStore } from "@/store/employeeStore";
import { toast } from 'vue3-toastify';
const employeeStore = useEmployeeStore();

const props = defineProps({
  data: {
    type: null,
    required: true,
  },
})

let edit_tfn = ref(false)
let edit_abn = ref(false)
let edit_status = ref(false)

const updateRow = async (row, data)=>{

var update = await employeeStore.updateRow({
    id: props.data.id,
    data,
    row
  })
  toast("Updated!")
  edit_tfn = false
  edit_abn = false
}
</script>

<template>
  <VCard class="mb-4">
    <VCardText>
      <p class="text-lg d-flex justify-space-between">
        <span>
          <b>Super Information</b>
        </span>
      </p>
      <VList class="card-list text-medium-emphasis">
        <VListItem>
          <VListItemTitle>
            <span class="font-weight-medium me-1"></span>
            <span v-if="!edit_tfn">{{ props.data.tax_number}}</span>
            <span v-else>
              <AppTextField
                v-model="props.data.tax_number"
                placeholder="123456"
                @keyup.enter="updateRow('tax_number', props.data.tax_number)"
              />
            </span>
          </VListItemTitle>
        </VListItem>
      </VList>
    </VCardText>
  </VCard>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 16px;
}
</style>
