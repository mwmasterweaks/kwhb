<script setup>
import { useDivisionStore } from "@/store/divisionStore";
import { useEmployeeStore } from "@/store/employeeStore";
import { useEmploymentStore } from "@/store/employmentStore";
import { useLocationStore } from "@/store/locationStore";
import { useRoleStore } from "@/store/roleStore";
import { requiredValidator } from '@core/utils/validators';
import { PerfectScrollbar } from 'vue3-perfect-scrollbar';


const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
  employee: {
    type: Object,
    required: true,
  },
})

const emit = defineEmits([
  'update:isDrawerOpen',
  'updateData',
])

const divisionStore = useDivisionStore()
const roleStore = useRoleStore();
const locationStore = useLocationStore()
const employmentStore = useEmploymentStore()
const employeeStore = useEmployeeStore()

const isFormValid = ref(false)
const refForm = ref()
const managers = ref([])


// 👉 drawer close
const closeNavigationDrawer = () => {
  emit('update:isDrawerOpen', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
  })
}

const fetchLineManager = async() => {
  managers.value = await employeeStore.fetch_line_managers({division_id: props.employee.division_id })
 
  managers.value = managers.value.map(approver => ({
        ...approver,
        title: `${approver.first_name} ${approver.last_name}`
    }));
    props.employee.manager_id = null;
    console.log("managers.value", managers.value);
}

const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      emit('updateData', {
        id: props.employee.id,
        user_id: props.employee.user.id,
        first_name: props.employee.first_name,
        last_name: props.employee.last_name,
        location_id: props.employee.location_id,
        division_id: props.employee.division_id,
        manager_id: props.employee.manager_id,
        job_title: props.employee.job_title,
        employment_id: props.employee.employment_id,
        role_id: props.employee.access_id,
        status: props.employee.status,
        // date_hired: dateHired.value,
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
      title="Add User"
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
                  v-model="employee.first_name"
                  :rules="[requiredValidator]"
                  label="First Name"
                  placeholder="Belinda Coles"
                />
              </VCol>
              <VCol cols="12">
                <AppTextField
                  v-model="employee.last_name"
                  :rules="[requiredValidator]"
                  label="Last Name"
                  placeholder="Katherine"
                />
              </VCol>

              <VCol cols="12">
                <AppSelect
                  v-model="employee.location_id"
                  label="Location"
                  placeholder="Katherine"
                  item-title="name"
                  item-value="id"
                  :rules="[requiredValidator]"
                  :items="locationStore.data.locations"
                />
              </VCol>

              <VCol cols="12">
                <AppSelect
                  v-model="employee.division_id"
                  label="Division"
                  placeholder="Program"
                  item-title="name"
                  item-value="id"
                  :rules="[requiredValidator]"
                  :items="divisionStore.data.divisions"
                  @update:modelValue="fetchLineManager"
                />
              </VCol>

              <VCol cols="12" v-if="managers.length > 0">
                <AppSelect
                  v-model="employee.manager_id"
                  label="Manager"
                  placeholder="Program"
                  item-title="title"
                  item-value="id"
                  :rules="[requiredValidator]"
                  :items="managers"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="employee.job_title"
                  :rules="[requiredValidator]"
                  label="Job Title"
                  placeholder="Physiotherapist"
                />
              </VCol>

              <VCol cols="12">
                <AppSelect
                  v-model="employee.employment_id"
                  label="Employment"
                  placeholder="Volunteer"
                  item-title="name"
                  item-value="id"
                  :rules="[requiredValidator]"
                  :items="employmentStore.data.employments"
                />
              </VCol>

              <VCol cols="12">
                <AppSelect
                  v-model="employee.access_id"
                  label="Access"
                  placeholder="User"
                  item-title="name"
                  item-value="id"
                  :rules="[requiredValidator]"
                  :items="roleStore.data.roles"
                />
              </VCol>

              <VCol cols="12">
                <AppSelect
                  v-model="employee.status"
                  label="Status"
                  placeholder="Active"
                  :rules="[requiredValidator]"
                  :items="employeeStore.data.statuses"
                />
              </VCol>

              <!-- <VCol cols="12">
                <AppDateTimePicker
                  v-model="dateHired"
                  label="Date Hired"
                  placeholder="28/02/2024"
                  :config="{dateFormat: 'd/m/Y' }"
                />
              </VCol> -->
              
              <VCol cols="12">
                <VBtn
                  type="submit"
                  class="me-3"
                >
                  Update
                </VBtn>
                <VBtn
                  type="reset"
                  variant="tonal"
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
