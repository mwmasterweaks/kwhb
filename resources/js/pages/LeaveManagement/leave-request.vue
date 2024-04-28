


<template>
  <div class="page-content-container">

    <section>
      <VCard
        title=""
        class="mb-6"
      >

        <VCardText class="d-flex flex-wrap py-6 gap-6" style="">
          <div class="me-12 d-flex justify-space-evenly gap-2" width="200" style="">
            <AppTextField
              class="filter-field"
              density="compact"
              v-model="filter.search_name"
              prepend-inner-icon="tabler-search"
              placeholder="Search by Name"
              persistent-placeholder
              @input="filter_change"
            />
            <AppSelect
              class="filter-field"
              density="compact"
              v-model="filter.leave_type"
              placeholder="Leave Type"
              item-title="name"
              item-value="id"
              :items="['all', ...leaveTypeStore.data.leave_types]"
              @update:modelValue="filter_change"
            />
            <!-- <AppDateTimePicker
              class="filter-field"
              density="compact"
              style="min-width: 150px;"
              v-model="filter.filter_date"
              placeholder="Select date"
              :config="{dateFormat: 'd/m/Y' }"
              multiple
            /> -->
             <VueDatePicker v-model="filter.filter_date" range multi-calendars @update:model-value="filter_change()" />
            <AppSelect
              class="filter-field"
              density="compact"
              v-model="filter.division"
              placeholder="Division"
              item-title="name"
              item-value="id"
              :items="['all', ...divisionStore.data.divisions]"
              @update:modelValue="filter_change"
            />
            <AppSelect
              class="filter-field"
              density="compact"
              v-model="filter.location"
              placeholder="Location"
              item-title="name"
              item-value="id"
              :items="['all', ...locationStore.data.locations]"
              @update:modelValue="filter_change"
            />
            <AppSelect
              class="filter-field"
              density="compact"
              v-model="filter.status"
              placeholder="Status"
              item-title="name"
              item-value="id"
              :items="['all','Pending','Declined','Approved', 'Cancelled']"
              @update:modelValue="filter_change"
            />
          </div>
          <VSpacer />

          <div class="app-user-search-filter d-flex align-center flex-wrap gap-4">
          
            <!-- 👉 Add user button -->
            <VBtn
              prepend-icon="tabler-plus"
              @click="applyLeaveVisible = true"
            >
              Apply for Leave
            </VBtn>
          </div>
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
            <template #item.date_from="{ item }">
              {{ $formatDate(item.raw.date_from) }} 
            </template>
            <template #item.date_to="{ item }">
              {{ $formatDate(item.raw.date_to) }} 
            </template>

            
             <template #item.action="{ item }">
              <div class="me-n3">
                <MoreBtn
                  :menu-list="moreList"
                  item-props
                  density="comfortable"
                />
              </div>
              <!--               
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

              </div> -->
             
            </template>
        </VDataTableServer>
      </VCard>
    </section>
    
    <DialogLeaveDetails
      v-model:isDialogVisible="leaveDetailsVisible"
      v-model:leaveDetail="item_selected"
    />
    
    <applyLeaveDialogVue
      v-model:isDialogVisible="applyLeaveVisible"
    ></applyLeaveDialogVue>

  </div>
  
</template>


<script setup>
import { useEmployeeStore } from "@/store/employeeStore";
import { useRouter } from 'vue-router';
import { toast } from 'vue3-toastify';

import DialogLeaveDetails from '@/pages/LeaveManagement/DialogLeaveDetails.vue';
import { useDivisionStore } from "@/store/divisionStore";
import { useEmploymentStore } from "@/store/employmentStore";
import { useLeaveStore } from "@/store/leaveStore";
import { useLeaveTypeStore } from "@/store/leaveTypeStore";
import { useLocationStore } from "@/store/locationStore";
import { debounce } from 'lodash';
import { VDataTableServer } from 'vuetify/labs/VDataTable';
import applyLeaveDialogVue from "./applyLeaveDialog.vue";


const router = useRouter()
const employeeStore = useEmployeeStore();
const leaveStore = useLeaveStore();
const divisionStore = useDivisionStore();
const locationStore = useLocationStore();
const employmentStore = useEmploymentStore();
const leaveTypeStore = useLeaveTypeStore();
let items = ref([]);
let item_selected = ref({});
const leaveDetailsVisible = ref(false)
onMounted( async() => {
  items.value = await leaveStore.fetchLeaveByApprover();
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
    title: 'Start Date',
    key: 'date_from',
  },
    {
    title: 'End Date',
    key: 'date_to',
  },
  {
    title: 'Full Name',
    key: 'full_name',
  },
  {
    title: 'Leave Type',
    key: 'leave_type.name',
  },
  {
    title: 'Duration',
    key: 'total_hours',
  },
  {
    title: 'Assigned To',
    key: 'assignedTo',
  },
  {
    title: 'Status',
    key: 'status',
  },
  {
    title: '',
    key: 'action',
  }
]
const moreList = [
  {
    title: 'View',
    value: 'view',
  },
  {
    title: 'Edit',
    value: 'edit',
  },
  {
    title: 'Delete',
    value: 'delete',
  },
]
const applyLeaveVisible = ref(false)
const filter = ref({
  search_name: '',
  leave_type: 'all',
  division: 'all',
  location: 'all',
  status: 'all',
  filter_date: null

})

const rowClick = (e, row)=>{
  //console.log("row",row.item);
  // employeeStore.data.employee_selected = row.item.raw;
  // // router.push("/EmployeeDetails");
  // router.push({ name: 'EmployeeDetails', params: { tab: 'EmployeeInfo' } })
  
}

const filter_change = debounce(async() => {
  console.log(filter.value);
  const payload = {
    filter: {
      search_name: filter.value.search_name ? true : false,
      leave_type: true,
      division: true,
      location: true,
      status: true,
      filter_date: filter.value.filter_date ? true : false
    },
    data: {
      search_name: filter.value.search_name,
      leave_type_id: filter.value.leave_type,
      division_id: filter.value.division,
      location_id: filter.value.location,
      status: filter.value.status,
      filter_date: {
        from: filter.value.filter_date ? filter.value.filter_date[0] : null,
        to: filter.value.filter_date ? filter.value.filter_date[1] : null,
      }
    }
  }
  items.value = await leaveStore.multipleFilter(payload);
  //console.log("item",item);
}, 800);

const showLeaveDetails = (item)=>{
  item_selected = item
  console.log(item_selected);
  leaveDetailsVisible.value = true;
}
const updateStatus = async (status, data)=>{
  console.log(data);

  var update = await leaveStore.updateRow({
    id: data.id,
    data: status,
    row: "status"
  })
  items.value = await leaveStore.fetchLeaveByApprover();
  toast("Updated!")
  console.log(update);
  
}
</script>
<style lang="scss" scoped>
.filter-field {
  min-width: 170px;
  max-width: 170px;
}
</style>

