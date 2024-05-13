<script setup>
import { useEmployeeStore } from "@/store/employeeStore"
import { computed, watch } from 'vue'
import { useRouter } from 'vue-router'

import EmployeeInfo from '@/pages/EmployeeDetails/EmployeeInfo/index.vue'
import LeaveInfo from '@/pages/EmployeeDetails/Leave/index.vue'
import PayInfo from '@/pages/EmployeeDetails/Pay/index.vue'
import UserProfileHeader from '@/pages/EmployeeDetails/UserProfileHeader.vue'


const router = useRouter()
const employeeStore = useEmployeeStore();

const activeTab = computed({
  get: () => employeeStore.data.routeTab,
  set: value => { employeeStore.setActiveTab(value) },
})

watch(activeTab, (newValue, oldValue) => {
  console.log('activeTab changed from', oldValue, 'to', newValue)
})

const tabs = [
  {
    title: 'Employee Info',
    icon: 'tabler-user-check',
    tab: 'EmployeeInfo',
  },
  {
    title: 'Pay',
    icon: 'tabler-credit-card', 
    tab: 'Pay',
  },
  {
    title: 'Leave',
    icon: 'tabler-calendar',
    tab: 'Leave',
  },
]
// if(!profileTabData.value)
// {
//   console.log("No employee selected");
//   router.push("employee")
// }
</script>

<template>
  <div>
    <UserProfileHeader class="mb-5" />
    <VTabs
      v-model="activeTab"
      class="v-tabs"
      color="#051c73"
    >
      <VTab
        v-for="item in tabs"
        :key="item.icon"
        :value="item.tab"
        class="font-weight-bold"
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
      v-model="activeTab"
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

<style lang="scss">
.custom-active-tab {
  background-color: #007bff !important; /* Set your desired background color */
  color: #fff !important; /* Set your desired text color */
}
</style>
