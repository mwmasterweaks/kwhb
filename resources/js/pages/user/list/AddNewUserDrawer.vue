<script setup>
import { useDivisionStore } from "@/store/divisionStore";
import { useEmploymentStore } from "@/store/employmentStore";
import { useLocationStore } from "@/store/locationStore";
import { PerfectScrollbar } from 'vue3-perfect-scrollbar';


  const divisionStore = useDivisionStore();
  const locationStore = useLocationStore();
  const employmentStore = useEmploymentStore();

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
const firstName = ref('')
const lastName = ref('')
const location = ref()
const division = ref()
const jobTitle = ref('')
const employment = ref()
const access = ref()
const access_list = ref()
const status = ref()
const dateHired = ref('')
access_list.value = [
  {name: "USER", id: "1"},
  {name: "LINE MANAGER", id: "2"},
  {name: "GENERAL MANAGER", id: "3"},
  {name: "HR", id: "4"},
  {name: "FINANCE", id: "5"},
  {name: "CEO", id: "6"},
  {name: "BOARD", id: "7"},
  {name: "APPLICANT", id: "8"},
];

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
        first_name:firstName.value,
        last_name:lastName.value,
        location_id:location.value,
        division_id:division.value,
        job_title:jobTitle.value,
        employment_id:employment.value,
        role_id:access.value,
        status:status.value,
        date_hired:dateHired.value,
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
                  v-model="firstName"
                  :rules="[requiredValidator]"
                  label="First Name"
                  placeholder="John"
                />
              </VCol>
              <VCol cols="12">
                <AppTextField
                  v-model="lastName"
                  :rules="[requiredValidator]"
                  label="Last Name"
                  placeholder="Doe"
                />
              </VCol>

              <VCol cols="12">
                <AppSelect
                  v-model="location"
                  label="Location"
                  placeholder="Select Location"
                  item-title="name"
                  item-value="id"
                  :rules="[requiredValidator]"
                  :items="locationStore.data.locations"
                />
              </VCol>

              <VCol cols="12">
                <AppSelect
                  v-model="division"
                  label="Division"
                  placeholder="Select Division"
                  item-title="name"
                  item-value="id"
                  :rules="[requiredValidator]"
                  :items="divisionStore.data.divisions"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="jobTitle"
                  :rules="[requiredValidator]"
                  label="Job Title"
                  placeholder="hr personnel"
                />
              </VCol>

               <VCol cols="12">
                <AppSelect
                  v-model="employment"
                  label="Employment"
                  placeholder="Select employment"
                  item-title="name"
                  item-value="id"
                  :rules="[requiredValidator]"
                  :items="employmentStore.data.employments"
                />
              </VCol>

              <VCol cols="12">
                <AppSelect
                  v-model="access"
                  label="Access"
                  placeholder="Select Access"
                  item-title="name"
                  item-value="id"
                  :rules="[requiredValidator]"
                  :items="access_list"
                />
              </VCol>

              <VCol cols="12">
                <AppSelect
                  v-model="status"
                  label="Status"
                  placeholder="Select Status"
                  :rules="[requiredValidator]"
                  :items="[{ title: 'Active', value: 'active' }, { title: 'Inactive', value: 'inactive' }, { title: 'Pending', value: 'pending' }]"
                />
              </VCol>

              <VCol cols="12">
                <AppDateTimePicker
                  v-model="dateHired"
                  label="Date Hired"
                  placeholder="Select date"
                  :config="{dateFormat: 'd/m/Y' }"
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
