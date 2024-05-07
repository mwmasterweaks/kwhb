


<template>
  <div class="page-content-container">
    <section>
      <h2>Leave Management</h2>
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
                  label="Select status"
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
        </VCardText>

        <VDivider />

        <!-- //EmployeeDetails-index -->
        <VDataTableServer
          :headers="headers"
          :items="items"
          :items-per-page="10"
          height="300"
          @click:row="rowClick"
        >
          <template #item.access="{ item }">
            <div v-if="item.raw.user.roles.length > 0">
              {{ item.raw.user.roles[0].name }}
            </div>
          </template>
          <template #item.full_name="{ item }">
            {{ item.raw.employee.first_name }} {{ item.raw.employee.last_name }}
          </template>
          <template #item.action="{ item }">
            <div style="display: flex;">
              <VBtn
                title="Approve"
                icon="tabler-thumb-up"
                variant="outlined"
                color="success"
                @click="updateStatus('Approved', item.raw)"
              />
              <VBtn
                title="Decline"
                icon="tabler-thumb-down"
                variant="outlined"
                color="error"
                @click="updateStatus('Declined', item.raw)"
              />
              <VBtn
                title="details"
                icon="tabler-list-details"
                variant="outlined"
                color="info"
                @click="showLeaveDetails(item.raw)"
              />
            </div>
          </template>
        </VDataTableServer>
      </VCard>
    </section>
    
    <DialogLeaveDetails
      v-model:isDialogVisible="leaveDetailsVisible"
      v-model:leaveDetail="item_selected"
    />
  </div>
</template>


<script setup>
import { useEmployeeStore } from "@/store/employeeStore"
import { useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'

import DialogLeaveDetails from '@/pages/LeaveManagement/DialogLeaveDetails.vue'
import { useDivisionStore } from "@/store/divisionStore"
import { useEmploymentStore } from "@/store/employmentStore"
import { useLeaveStore } from "@/store/leaveStore"
import { useLocationStore } from "@/store/locationStore"
import { VDataTableServer } from 'vuetify/labs/VDataTable'


const router = useRouter()
const employeeStore = useEmployeeStore()
const leaveStore = useLeaveStore()
const divisionStore = useDivisionStore()
const locationStore = useLocationStore()
const employmentStore = useEmploymentStore()
let items = ref([])
let item_selected = ref({})
const leaveDetailsVisible = ref(false)

onMounted( async() => {
  items.value = await leaveStore.fetchLeaveByApprover()

  //fetchLeaveByApprover
  //await divisionStore.setDivisions();
  //await locationStore.setLocations();
  //await employmentStore.setEmployments(); 
    
  //items.value = employeeStore.data.employees;
})

const headers = [
  {
    title: 'ID',
    key: 'id',
  },
  {
    title: 'Full Name',
    key: 'full_name',
  },
  {
    title: 'Job Title',
    key: 'employee.job_title',
  },
  {
    title: 'Date Filed',
    key: 'date_filed',
  },
  {
    title: 'Date From',
    key: 'date_from',
  },
  {
    title: 'Date To',
    key: 'date_to',
  },
  {
    title: 'Leave Type',
    key: 'leave_type.name',
  },
  {
    title: 'total_hours',
    key: 'total_hours',
  },
  {
    title: 'Action',
    key: 'action',
  },
]
  

const rowClick = (e, row)=>{
  //console.log("row",row.item);
  // employeeStore.data.employee_selected = row.item.raw;
  // // router.push("/EmployeeDetails");
  // router.push({ name: 'EmployeeDetails', params: { tab: 'EmployeeInfo' } })
    
}

const showLeaveDetails = item=>{
  item_selected = item
  console.log(item_selected)
  leaveDetailsVisible.value = true
}

const updateStatus = async (status, data)=>{
  console.log(data)

  var update = await leaveStore.updateRow({
    id: data.id,
    data: status,
    row: "status",
  })
  items.value = await leaveStore.fetchLeaveByApprover()
  toast("Updated!")
  console.log(update)
    
}
</script>


