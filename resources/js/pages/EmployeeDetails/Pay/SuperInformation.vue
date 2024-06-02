<script setup>
import myob_emp from '@/api/employee/myob_employee'
import { useEmployeeStore } from "@/store/employeeStore"
import { toast } from 'vue3-toastify'

const props = defineProps({
  data: {
    type: null,
    required: true,
  },
})

const employeeStore = useEmployeeStore()

let edit_tfn = ref(false)
let edit_abn = ref(false)
let edit_status = ref(false)

onMounted( async() => {
  let myob_data = await myob_emp.fetchEmployeeByDisplayID(props.data.id)
  
  console.log('super information data', myob_data)
})

const updateRow = async (row, data)=>{

  var update = await employeeStore.updateRow({
    id: props.data.id,
    data,
    row,
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
            <span class="font-weight-medium me-1" />
            <span v-if="!edit_tfn">{{ props.data.tax_number }}</span>
            <span v-else>
              <AppTextField
                v-model="props.data.tax_number"
                placeholder="123456"
                @keyup.enter="updateRow('tax_number', props.data.tax_number)"
              />
            </span>
            <div class="hesta-data">
              <p class="text-lg d-flex justify-space-between">
                <span class="hesta-title">
                  HESTA
                </span>
                <span>
                  <VBtn
                    class="me-2"
                    variant="tonal"
                    color="#092076"
                  >
                    <b>Edit</b>
                  </VBtn>
                  <VBtn
                    variant="tonal"
                    color="#f1b63a"
                  >
                    <b>Delete</b>
                  </VBtn>
                </span>
              </p>
              <p>{{ props.data.first_name + " " + props.data.last_name }}</p>
              <p><b>Account Number: </b>{{ myob_data }}</p>
            </div>
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

.hesta-data {
  padding: 20px;
  border-radius: 7px;
  background: #f8f8f9;
  margin-block-start: -15px;
}

.hesta-title {
  padding: 10px;
  border-inline-end: 5px solid #7226e0;
  border-inline-start: 5px solid #7226e0;
  color: #7226e0;
  font-size: x-large;
  font-weight: 800;
  margin-inline-end: auto;
}

.action-buttons {
  justify-content: flex-end;
}
</style>
