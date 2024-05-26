<!-- 

space
 -->
<script setup>
import { useEmployeeStore } from "@/store/employeeStore";

const employeeStore = useEmployeeStore();

const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
})

const emit = defineEmits([
  'update:isDrawerOpen',
  'userData',
])

const isFormValid = ref(false)
const refForm = ref()
const bsb = ref('')
const account_name = ref('')
const account = ref('')
const primary = ref(false)
const reimbursement = ref(false)

// 👉 drawer close
const closeNavigationDrawer = () => {
  emit('update:isDrawerOpen', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
  })
}

const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      emit('userData', {
        bsb:bsb.value,
        account_name:account_name.value,
        account:account.value,
        primary:primary.value,
        reimbursement:reimbursement.value,
        employee_id:employeeStore.data.employee_selected.id,
      })
      emit('update:isDrawerOpen', false)
      nextTick(() => {
        refForm.value?.reset()
        refForm.value?.resetValidation()
      })
    }
  })
}

const handleDrawerModelValueUpdate = val => {
  emit('update:isDrawerOpen', val)
}
</script>

<template>
  <VNavigationDrawer
    temporary
    :width="400"
    location="end"
    class="scrollable-content"
    :model-value="props.isDrawerOpen"
    @update:model-value="handleDrawerModelValueUpdate"
  >
    <AppDrawerHeaderSection
      title="Add Bank Information"
      @cancel="closeNavigationDrawer"
    />

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <VForm
            ref="refForm"
            v-model="isFormValid"
            @submit.prevent="onSubmit"
          >
            <VRow>
             <VCol cols="12">
                <AppTextField
                  v-model="account_name"
                  :rules="[requiredValidator]"
                  label="Account Name"
                  placeholder="John Doe"
                />
              </VCol>
              <VCol cols="12">
                <AppTextField
                  v-model="bsb"
                  :rules="[requiredValidator]"
                  label="BSB"
                  placeholder="123-234"
                />
              </VCol>
              
              <VCol cols="12">
                <AppTextField
                  v-model="account"
                  :rules="[requiredValidator]"
                  label="Account"
                  placeholder="0864 54343"
                />
              </VCol>
              <VCol cols="12">
                <VCheckbox
                  v-model="primary"
                  :rules="[requiredValidator]"
                  label="Primary"
                />
              </VCol>
              <VCol cols="12">
                <VCheckbox
                  v-model="reimbursement"
                  :rules="[requiredValidator]"
                  label="Reimbursement Account"
                />
              </VCol>
              
              
              <VCol cols="12">
                <VBtn
                  type="submit"
                  class="me-3"
                >
                  Submit
                </VBtn>
                <VBtn
                  type="reset"
                  variant="outlined"
                  color="secondary"
                  @click="closeNavigationDrawer"
                >
                  Cancel
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
