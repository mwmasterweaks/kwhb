


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
                <AppSelect
                  v-model="filter.role"
                  placeholder="Select Role"
                  item-title="name"
                  item-value="id"
                  :items=" ['all', ...roleStore.data.roles]"
                  @update:modelValue="filter_change"
                />
              </div>
            </VCol>
            <VCol
              cols="12"
              sm="4"
            >
              <div class="app-select flex-grow-1">
                <AppSelect
                  v-model="filter.division"
                  placeholder="Select Division"
                  item-title="name"
                  item-value="id"
                  :items="['all', ...divisionStore.data.divisions]"
                  @update:modelValue="filter_change"
                />
              </div>
            </VCol>
            <VCol
              cols="12"
              sm="4"
            >
              <div class="app-select flex-grow-1">
                <AppSelect
                  v-model="filter.status"
                  placeholder="Select Status"
                  :items="['all', ...employeeStore.data.statuses]"
                  @update:modelValue="filter_change"
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
                { value: 5, title: '5' },
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
                @input="search_change"
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
              Add new user
            </VBtn>
          </div>
        </VCardText>

        <VDivider />

        <!-- //EmployeeDetails-index -->
        <VDataTableServer
          :items-per-page="itemsPerPage"
          :page="page"
          :items="items"
          :items-length="totalEmployees"
          :headers="headers"
          :header-props="{ class: 'custom-header-style' }"
          :search="searchQuery"
          @click:row="rowClick"
          @update:options="updateOptions"
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
                font-weight: 600;
                text-align: center;"
              :style="{ 
                background: getBackgroundColor(item, 'status'),
                color: getTextColor(item, 'status')
              }"
            >
              {{ item.raw.status.charAt(0).toUpperCase() + item.raw.status.slice(1) }}
            </div>
          </template>
          <template #bottom>
            <VDivider />
            <div class="d-flex align-center justify-sm-space-between justify-center flex-wrap gap-3 pa-5 pt-3">
              <p
                class="text-sm text-disabled mb-0"
                style="color: #999 !important;"
              >
                {{ generateRangeText(page, totalEmployees, itemsPerPage) }}
              </p>

              <VPagination
                v-model="page"
                :total-visible="6"
                :length="Math.ceil(totalEmployees / itemsPerPage)"
              >
                <template #prev="slotProps">
                  <VBtn
                    variant="tonal"
                    color="default"
                    v-bind="slotProps"
                    :icon="false"
                  >
                    Previous
                  </VBtn>
                </template>

                <template #next="slotProps">
                  <VBtn
                    variant="tonal"
                    color="default"
                    v-bind="slotProps"
                    :icon="false"
                  >
                    Next
                  </VBtn>
                </template>
              </VPagination>
            </div>
          </template>
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
import { ProfilePlaceHolder } from '@/plugins/profilePlaceHolder'
import { useDivisionStore } from "@/store/divisionStore"
import { useEmployeeStore } from "@/store/employeeStore"
import { useEmploymentStore } from "@/store/employmentStore"
import { useLocationStore } from "@/store/locationStore"
import { useRoleStore } from "@/store/roleStore"

//import { paginationMeta } from '@api-utils/paginationMeta';
import { debounce } from 'lodash'
import JsonCSV from 'vue-json-csv'
import { useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'
import { VDataTableServer } from 'vuetify/labs/VDataTable'


const router = useRouter()
const employeeStore = useEmployeeStore()
const divisionStore = useDivisionStore()
const locationStore = useLocationStore()
const employmentStore = useEmploymentStore()
const roleStore = useRoleStore()
var data_to_export = ref([])
let items = ref([])
const itemsData= ref({})
const searchQuery = ref('')

// Data table options
const itemsPerPage = ref(5)
const totalEmployees = ref(0)
const page = ref(1)
const sortBy = ref()
const orderBy = ref()

const updateOptions = async  options =>  {
  itemsPerPage.value = options.itemsPerPage
  page.value = options.page
  sortBy.value = options.sortBy[0]?.key
  orderBy.value = options.sortBy[0]?.order

  //items.value  = await employeeStore.setEmployees({page:newValue})
}

const filter = ref({
  role: 'Select Role',
  division: 'Select Division',
  status: 'Select Status',

})

onMounted( async() => {
  
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

// const reloadTable = async() => {
//   itemsData.value = await employeeStore.setEmployees({page:page.value, itemsPerPage: newValue, search: searchQuery.value})
//   items.value = itemsData.value.data;
// }

async function reloadTable() {
  itemsData.value = await employeeStore.setEmployees({ page: page.value, itemsPerPage: itemsPerPage.value, search: searchQuery.value })
  items.value = itemsData.value.data
  itemsPerPage.value = itemsData.value.per_page
  totalEmployees.value = itemsData.value.total
}
watch(page, async newValue => {
  if(newValue != null){
    await reloadTable()
  }
}, { immediate: true })

watch(itemsPerPage, async newValue => {
  if(newValue != null){
    page.value = 1
    await reloadTable()
  }
}, { immediate: true })

const search_change = debounce(async() => {
  page.value = 1
  await reloadTable()
  items.value = await leaveStore.multipleFilter(payload)
}, 800)

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

const rowClick = (e, row)=>{
  employeeStore.data.employee_selected = row.item.raw
  console.log("employee_selected :", employeeStore.data.employee_selected );
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
  //console.log('export data', data_to_export)

}

const getBackgroundColor = (item, value) => {
  if (item.raw.user.roles.length > 0 && value == 'employee') {
    //console.log("role", item.raw.user.roles[0].name);
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
    case 'Extended leave':
      return '#FDF3DE'
    case 'Terminated':
      return '#FBDFE1'
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
    case 'Extended leave':
      return '#f1b434'
    case 'Terminated':
      return '#E7404C'
    default:
      return '#363636'
    }
  }

  return '#363636'
}

const filter_change = debounce(async() => {
  const payload = {
    filter: {
      role: true,
      division: true,
      status: true,
    },
    data: {
      role_id: filter.value.role,
      division_id: filter.value.division,
      status: filter.value.status,
    },
  }

  console.log("payload", payload)
  items.value = await employeeStore.multipleFilter(payload)
}, 800)

const generateRangeText = (pageNumber, totalNumberOfRows, rowsPerPage) => {
  const startIndex = (pageNumber - 1) * rowsPerPage + 1
  let endIndex = pageNumber * rowsPerPage
  if (endIndex > totalNumberOfRows) {
    endIndex = totalNumberOfRows
  }

  const rowsOnLastPage = totalNumberOfRows % rowsPerPage || rowsPerPage

  let rangeText
  if (totalNumberOfRows === 0) {
    rangeText = "No entries"
  } else if (pageNumber === 1 && endIndex === totalNumberOfRows) {
    rangeText = `Showing all of ${totalNumberOfRows} entries`
  } else if (pageNumber === 1) {
    rangeText = `Showing ${startIndex} to ${endIndex} of ${totalNumberOfRows} entries`
  } else if (endIndex === totalNumberOfRows) {
    if (rowsOnLastPage === 1) {
      rangeText = `Showing last entry of ${totalNumberOfRows} entries`
    } else {
      rangeText = `Showing ${startIndex} to ${endIndex} of ${totalNumberOfRows} entries`
    }
  } else {
    rangeText = `Showing ${startIndex} to ${endIndex} of ${totalNumberOfRows} entries`
  }

  return rangeText
}
</script>
