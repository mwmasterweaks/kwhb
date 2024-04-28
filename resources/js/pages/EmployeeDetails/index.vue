<script setup>
import { useEmployeeStore } from "@/store/employeeStore";
import { useRouter } from 'vue-router';

import EmployeeInfo from '@/pages/EmployeeDetails/EmployeeInfo/index.vue';
import LeaveInfo from '@/pages/EmployeeDetails/Leave/index.vue';
import PayInfo from '@/pages/EmployeeDetails/Pay/index.vue';
import UserProfileHeader from '@/pages/EmployeeDetails/UserProfileHeader.vue';


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


const routeTab = ref('EmployeeInfo')

const activeTab = computed({
  get: () => routeTab,
  set: () => routeTab,
})

// tabs
const tabs = [
  {
    title: 'EmployeeInfo',
    icon: 'tabler-user-check',
    tab: 'EmployeeInfo',
  },
  {
    title: 'Pay',
    icon: 'tabler-users', 
    tab: 'Pay',
  },
  {
    title: 'Leave',
    icon: 'tabler-layout-grid',
    tab: 'Leave',
  },
]

  const router = useRouter()
  const employeeStore = useEmployeeStore();
  console.log(employeeStore.data.employee_selected.first_name);
  if(employeeStore.data.employee_selected.first_name == null)
  {
    router.push("employee")
  }

</script>

<template>
  <div>
    <UserProfileHeader class="mb-5" />
    <VTabs
      v-model="activeTab"
      class="v-tabs-pill"
    >
      <VTab
        v-for="item in tabs"
        :key="item.icon"
        :value=item.tab
      >
        <VIcon
          size="20"
          start
          :icon="item.icon"
        />
        {{ item.title }}
      </VTab>
    </VTabs>

    <VWindow
      v-model="routeTab"
      class="mt-5 disable-tab-transition"
    >
      <VWindowItem value="EmployeeInfo">
        <EmployeeInfo />
      </VWindowItem>

      <VWindowItem value="Pay">
        <PayInfo />
      </VWindowItem>

      <VWindowItem value="Leave">
        <LeaveInfo />
      </VWindowItem>

    </VWindow>
  </div>
</template>
