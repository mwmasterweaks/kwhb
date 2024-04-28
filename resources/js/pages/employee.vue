


<template>
  <div class="page-content-container">

    <section>
      <div class="d-flex mb-6">
        <VRow>
           <template
          v-for="(data, id) in widgetData"
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
            <VCol cols="12" sm="4">
              <div class="app-select flex-grow-1">
                <v-autocomplete
                  label="Select Role"
                  :items="['role1', 'role2', 'role3', 'role4', 'role5', 'role6']"
                ></v-autocomplete>
              </div>
            </VCol>
            <VCol cols="12" sm="4">
              <div class="app-select flex-grow-1">
                <v-autocomplete
                  label="Search Division"
                  :items="['division1', 'division2', 'division3', 'division4', 'division5', 'division6']"
                ></v-autocomplete>
              </div>
            </VCol>
            <VCol cols="12" sm="4">
              <div class="app-select flex-grow-1">
                <v-autocomplete
                  label="Select status"
                  :items="['status1', 'status2', 'status3', 'status4', 'status5', 'status6']"
                ></v-autocomplete>
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
              color="secondary"
              prepend-icon="tabler-screen-share"
            >
              Export
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
            <template #bottom></template>
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
import AddNewUserDrawer from '@/pages/user/list/AddNewUserDrawer.vue';
import { VDataTableServer } from 'vuetify/labs/VDataTable';

import { useEmployeeStore } from "@/store/employeeStore";

import { useRouter } from 'vue-router';

import { ProfilePlaceHolder } from '@/plugins/profilePlaceHolder';
import { useDivisionStore } from "@/store/divisionStore";
import { useEmploymentStore } from "@/store/employmentStore";
import { useLocationStore } from "@/store/locationStore";
import { toast } from 'vue3-toastify';


  const router = useRouter()
  const employeeStore = useEmployeeStore();
  const divisionStore = useDivisionStore();
  const locationStore = useLocationStore();
  const employmentStore = useEmploymentStore();
  let items = ref([]);
  onMounted( async() => {
    await employeeStore.setEmployees();
    //await divisionStore.setDivisions();
    //await locationStore.setLocations();
    //await employmentStore.setEmployments(); 
    
    items.value = employeeStore.data.employees;
  })

  const headers = [
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
    const initials = userData.first_name.charAt(0).toUpperCase() + userData.last_name.charAt(0).toUpperCase();
    console.log(initials);
    userData.profile = ProfilePlaceHolder(initials)
    console.log(userData);
    const addEmp = await employeeStore.addEmployee(userData);
    items.value = addEmp
    if(!addEmp)  toast("Error please try again!")
    else  {
      toast("Added!")
      isAddNewUserDrawerVisible.value = false
    } 
  }
  
  const widgetData = ref([
    {
      title: 'Active Employees',
      value: '250',
      change: 15,
      desc: 'Active Total Employees',
      icon: 'tabler-user',
      iconColor: 'primary',
    },
    {
      title: 'Offswing',
      value: '25',
      change: -18,
      desc: 'Fixed Period Contract',
      icon: 'tabler-user-plus',
      iconColor: 'error',
    },
    {
      title: ' Pending Employees',
      value: '5',
      change: 98,
      desc: 'Year to Date',
      icon: 'tabler-user-check',
      iconColor: 'success',
    },
    {
      title: 'Extended Leave',
      value: '3',
      change: -4,
      desc: 'Year to Date',
      icon: 'tabler-user-exclamation',
      iconColor: 'warning',
    },
  ])

  const rowClick = (e, row)=>{
    console.log("row",row.item);
    employeeStore.data.employee_selected = row.item.raw;
    // router.push("/EmployeeDetails");
    router.push({ name: 'EmployeeDetails', params: { tab: 'EmployeeInfo' } })
    
  }
</script>


