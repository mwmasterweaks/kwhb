


<template>
  <div class="page-content-container">
    <section>
      <div class="mb-6 font-weight-bold text-h2">
        <span style="color: #808494;">Employee / </span>
        <span style="color: #000829;">List</span>
      </div>
      <div class="d-flex mb-6">
        <VRow>
          <template
            v-for="(data, id) in employeeStore.data.widget"
            :key="id"
          >
            <VCol
              cols="12"
              md="3"
              sm="6"
            >
              <VCard>
                <VCardText>
                  <div class="d-flex justify-space-between">
                    <div class="d-flex flex-column gap-y-1">
                      <span class="text-body-1 text-medium-emphasis">{{ data.title }}</span>
                      <div>
                        <h4 class="text-h4">
                          {{ data.value }}
                          <span
                            class="text-base "
                            :class="data.change > 0 ? 'text-success' : 'text-error'"
                          >({{ data.change }}%)</span>
                        </h4>
                      </div>
                      <span class="text-sm">{{ data.desc }}</span>
                    </div>
                    <VAvatar
                      :color="data.iconColor"
                      variant="tonal"
                      rounded
                      size="38"
                    >
                      <VIcon
                        :icon="data.icon"
                        size="26"
                      />
                    </VAvatar>
                  </div>
                </VCardText>
              </VCard>
            </VCol>
          </template>
        </VRow>
      </div>
      <VCard
        title="Search Filter"
        class="mb-6"
      >
        <VCardText>
          <VRow>
            <VCol
              cols="12"
              sm="4"
            >
              <div class="app-select flex-grow-1">
                <VAutocomplete
                  label="Select Role"
                  :items="['role1', 'role2', 'role3', 'role4', 'role5', 'role6']"
                />
              </div>
            </VCol>
            <VCol
              cols="12"
              sm="4"
            >
              <div class="app-select flex-grow-1">
                <VAutocomplete
                  label="Search Division"
                  :items="['division1', 'division2', 'division3', 'division4', 'division5', 'division6']"
                />
              </div>
            </VCol>
            <VCol
              cols="12"
              sm="4"
            >
              <div class="app-select flex-grow-1">
                <VAutocomplete
                  label="Select Status"
                  :items="['status1', 'status2', 'status3', 'status4', 'status5', 'status6']"
                />
              </div>
            </VCol>
          </VRow>
        </VCardText>
        <hr>
        <br>
        

        <VCardText class="d-flex flex-wrap py-4 gap-4">
          <div class="me-3 d-flex gap-3">
            <AppSelect
              :model-value="itemsPerPage"
              :items="[
                { value: 10, title: '10' },
                { value: 25, title: '25' },
                { value: 50, title: '50' },
                { value: 100, title: '100' },
                { value: -1, title: 'All' },
              ]"
              style="inline-size: 6.25rem;"
              @update:model-value="itemsPerPage = parseInt($event, 10)"
            />
          </div>
          <VSpacer />

          <div class="app-user-search-filter d-flex align-center flex-wrap gap-4">
            <!-- 👉 Search  -->
            <div style="inline-size: 10rem;">
              <AppTextField
                v-model="searchQuery"
                placeholder="Search"
                density="compact"
              />
            </div>

            <!-- 👉 Export button -->
            <VBtn
              variant="tonal"
              color="primary"
              prepend-icon="tabler-screen-share"
              @click.stop="exportData"
            >
              <JsonCSV
                id="expdata"
                class="btn btn-default"
                :data="data_to_export"
                name="filename.csv"
              >
                Export
              </JsonCSV>
            </VBtn>

            <!-- 👉 Add user button -->
            <VBtn
              prepend-icon="tabler-plus"
              @click="isAddNewUserDrawerVisible = true"
            >
              Add New User
            </VBtn>
          </div>
        </VCardText>

        <VDivider />

        <!-- //EmployeeDetails-index -->
        <VDataTableServer
          :headers="headers"
          :header-props="{ class: 'custom-header-style' }"
          :items="items"
          :items-per-page="10"
          height="300"
          @click:row="rowClick"
        >
          <template #item.employee="{ item }">
            <div>
              <div
                style="display: flex;
                  width: 45%;
                  align-items: center;
                  justify-content: center;
                  border-radius: 50px;
                  aspect-ratio: 1;
                  font-weight: bold;"
                :style="{ 
                  background: getBackgroundColor(item, 'employee'),
                  color: getTextColor(item, 'employee')
                }"
              >
                {{ item.raw.first_name.charAt(0).toUpperCase() + item.raw.last_name.charAt(0).toUpperCase() }}
              </div>
            </div>
          </template>
          <template #item.first_name="{ item }">
            <div style="font-weight: bold;">
              {{ item.raw.first_name }}
            </div>
          </template>
          <template #item.last_name="{ item }">
            <div style="font-weight: bold;">
              {{ item.raw.last_name }}
            </div>
          </template>
          <template #item.access="{ item }">
            <div v-if="item.raw.user.roles.length > 0">
              {{ item.raw.user.roles[0].name }}
            </div>
          </template>
          <template #item.status="{ item }">
            <div
              style="border-radius: 5px;
                text-align: center;"
              :style="{ 
                background: getBackgroundColor(item, 'status'),
                color: getTextColor(item, 'status')
              }"
            >
              {{ item.raw.status.charAt(0).toUpperCase() + item.raw.status.slice(1) }}
            </div>
          </template>
          <template #bottom />
        </VDataTableServer>
      </VCard>
      <AddNewUserDrawer
        v-model:isDrawerOpen="isAddNewUserDrawerVisible"
        @user-data="addNewUser"
      />
    </section>
  </div>
</template>


<script setup>
import AddNewUserDrawer from '@/pages/user/list/AddNewUserDrawer.vue'
import JsonCSV from 'vue-json-csv'
import { VDataTableServer } from 'vuetify/labs/VDataTable'

import { useEmployeeStore } from "@/store/employeeStore"

import { useRouter } from 'vue-router'

import { ProfilePlaceHolder } from '@/plugins/profilePlaceHolder'
import { useDivisionStore } from "@/store/divisionStore"
import { useEmploymentStore } from "@/store/employmentStore"
import { useLocationStore } from "@/store/locationStore"
import { toast } from 'vue3-toastify'


const router = useRouter()
const employeeStore = useEmployeeStore()
const divisionStore = useDivisionStore()
const locationStore = useLocationStore()
const employmentStore = useEmploymentStore()
var data_to_export = ref([])
let items = ref([])

onMounted( async() => {
  await employeeStore.setEmployees()

  //await divisionStore.setDivisions();
  //await locationStore.setLocations();
  //await employmentStore.setEmployments(); 
    
  items.value = employeeStore.data.employees

  exportData()
})

const headers = [
  {
    title: 'Employee',
    key: 'employee',
  },
  {
    title: 'First Name',
    key: 'first_name',
  },
  {
    title: 'Last Name',
    key: 'last_name',
  },
  {
    title: 'Location',
    key: 'location.name',
    class: 'font-weight-bold',
  },
  {
    title: 'Division',
    key: 'division.name',
  },
  {
    title: 'Job Title',
    key: 'job_title',
  },
  {
    title: 'Employment',
    key: 'employment.name',
  },
  {
    title: 'Access',
    key: 'access',
  },
  {
    title: 'Status',
    key: 'status',
  },
]

const isAddNewUserDrawerVisible = ref(false)

const addNewUser = async userData => {
  const initials = userData.first_name.charAt(0).toUpperCase() + userData.last_name.charAt(0).toUpperCase()

  console.log(initials)
  userData.profile = ProfilePlaceHolder(initials)
  console.log(userData)

  const addEmp = await employeeStore.addEmployee(userData)

  items.value = addEmp
  if(!addEmp)  toast("Error please try again!")
  else  {
    toast("Added!")
    isAddNewUserDrawerVisible.value = false
  } 
}
  
// const widgetData = ref([
//   {
//     title: 'Active Employees',
//     value: '250',
//     change: 15,
//     desc: 'Active Total Employees',
//     icon: 'tabler-user',
//     iconColor: 'primary',
//   },
//   {
//     title: 'Offswing',
//     value: '25',
//     change: -18,
//     desc: 'Fixed Period Contract',
//     icon: 'tabler-user-plus',
//     iconColor: 'error',
//   },
//   {
//     title: ' Pending Employees',
//     value: '5',
//     change: 98,
//     desc: 'Year to Date',
//     icon: 'tabler-user-check',
//     iconColor: 'success',
//   },
//   {
//     title: 'Extended Leave',
//     value: '3',
//     change: -4,
//     desc: 'Year to Date',
//     icon: 'tabler-user-exclamation',
//     iconColor: 'warning',
//   },
// ])

const rowClick = (e, row)=>{
  console.log("row", row.item)
  employeeStore.data.employee_selected = row.item.raw

  // router.push("/EmployeeDetails");
  router.push({ name: 'EmployeeDetails', params: { tab: 'EmployeeInfo' } })
    
}

const exportData = () => {
  data_to_export = items.value.map(item => ({
    first_name: item.first_name,
    last_name: item.last_name,
    location: item.location.name,
    division: item.division.name,
    job_title: item.job_title,
    employment: item.employment.name,
    access: item.user && item.user.roles && item.user.roles.length > 0 ? item.user.roles[0].name : '',
    status: item.status,
  }))
  console.log('export data', data_to_export)

}

const getBackgroundColor = (item, value) => {
  if (item.raw.user.roles.length > 0 && value == 'employee') {
    switch (item.raw.user.roles[0].name) {
    case 'Administrator':
      return '#f9dccd'
    case 'Applicant':
      return '#fcdef0'
    case 'Board':
      return '#ccd1e3'
    case 'CEO':
      return '#fcf0d6'
    case 'Finance':
      return '#fad7da'
    case 'General Manager':
      return '#ebdffa'
    case 'HR':
      return '#9b5de5'
    case 'Line Manager':
      return '#b9f3c7'
    case 'User':
      return '#70f0e7'
    default:
      return '#cccccc'
    }
  } else if (item.raw.status && value == 'status') {
    switch (item.raw.status.charAt(0).toUpperCase() + item.raw.status.slice(1)) {
    case 'Active':
      return '#e6f5e9'
    case 'Offswing':
      return '#fae3d7'
    case 'Pending':
      return '#e5eaff'
    case 'Extended Leave':
      return '#fdf3de'
    default:
      return '#cccccc'
    }
  }

  return '#cccccc'
}

const getTextColor = (item, value) => {
  if (item.raw.user.roles.length > 0 && value == 'employee') {
    switch (item.raw.user.roles[0].name) {
    case 'Administrator':
      return '#e35205'
    case 'Applicant':
      return '#f15bb5'
    case 'Board':
      return '#001871'
    case 'CEO':
      return '#f1b434'
    case 'Finance':
      return '#e63946'
    case 'General Manager':
      return '#9b5de5'
    case 'HR':
      return '#f1b434'
    case 'Line Manager':
      return '#074625'
    case 'User':
      return '#0a7069'
    case 'Active':
      return '#62c379'
    default:
      return '#363636'
    } 
  } else if (item.raw.status && value == 'status') {
    switch (item.raw.status.charAt(0).toUpperCase() + item.raw.status.slice(1)) {
    case 'Active':
      return '#61c378'
    case 'Offswing':
      return '#e35205'
    case 'Pending':
      return '#5c7fff'
    case 'Extended Leave':
      return '#f1b434'
    default:
      return '#363636'
    }
  }

  return '#363636'
}
</script>
