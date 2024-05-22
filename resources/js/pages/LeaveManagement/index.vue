<script setup>
import LeaveCalendar from '@/pages/LeaveManagement/leave-calendar.vue'
import LeaveRequest from '@/pages/LeaveManagement/leave-request.vue'


//const employeeStore = useEmployeeStore();

// console.log(employeeStore.data.employee_selected);
// import { useRoute } from 'vue-router';

// definePage({
//   meta: {
//     navActiveLink: 'EmployeeDetails',
//     key: 'tab',
//   },
// })

// const route = useRoute('EmployeeDetails')

// console.log("route.params.tab", route.params.tab)


const routeTab = ref('LeaveRequest')

const activeTab = computed({
  get: () => routeTab,
  set: () => routeTab,
})

// tabs
const tabs = [
  {
    title: 'Leave Request',
    tab: 'LeaveRequest',
  },
  {
    title: 'Leave Calendar',
    tab: 'LeaveCalendar',
  },
]

// const router = useRouter()
// const employeeStore = useEmployeeStore();
// console.log(employeeStore.data.employee_selected.first_name);
// if(employeeStore.data.employee_selected.first_name == null)
// {
//   router.push("employee")
// }
</script>

<template>
  <div>
    <h1>Leave Management</h1>
    <VTabs
      v-model="activeTab"
      class="v-tabs-pill"
    >
      <VTab
        v-for="item in tabs"
        :key="item.title"
        :value="item.tab"
      >
        {{ item.title }}
      </VTab>
    </VTabs>

    <VWindow
      v-model="routeTab"
      class="mt-5 disable-tab-transition"
    >
      <VWindowItem value="LeaveRequest">
        <LeaveRequest />
      </VWindowItem>

      <VWindowItem value="LeaveCalendar">
        <LeaveCalendar />
      </VWindowItem>
    </VWindow>
  </div>
</template>
