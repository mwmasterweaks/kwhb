<script setup>
import { useEmployeeStore } from "@/store/employeeStore"
import { computed, watch } from 'vue'
import { useRouter } from 'vue-router'

import EmployeeInfo from '@/pages/EmployeeDetails/EmployeeInfo/index.vue'
import LeaveInfo from '@/pages/EmployeeDetails/Leave/index.vue'
import PayInfo from '@/pages/EmployeeDetails/Pay/index.vue'
import UserProfileHeader from '@/pages/EmployeeDetails/UserProfileHeader.vue'
import { leave_tab_icon, pay_tab_icon } from '@/plugins/svg_handlers.js'


let c_leave_tab_icon = leave_tab_icon
let c_pay_tab_icon = pay_tab_icon
const router = useRouter()
const employeeStore = useEmployeeStore()

const activeTab = computed({
  get: () => employeeStore.data.routeTab,
  set: value => { employeeStore.setActiveTab(value) },
})

watch(activeTab, (newValue, oldValue) => {
  //console.log('activeTab changed from', oldValue, 'to', newValue)
  c_leave_tab_icon = c_leave_tab_icon.replace(/fill:#051C73/g, "fill:#383E59");
  c_pay_tab_icon = c_pay_tab_icon.replace(/fill:#051C73/g, "fill:#383E59");

  if(newValue == "Leave")
    c_leave_tab_icon = c_leave_tab_icon.replace(/fill:#383E59/g, "fill:#051C73")
  if(newValue == "Pay")
    c_pay_tab_icon = c_pay_tab_icon.replace(/fill:#383E59/g, "fill:#051C73")
})

const tabs = [
  {
    title: 'Employee Info',
    icon: 'tabler-user-check',
    tab: 'EmployeeInfo',
  },
  {
    title: 'Pay',
    icon: 'customPay', 
    tab: 'Pay',
  },
  {
    title: 'Leave',
    icon: 'customLeave',
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
        class="font-weight-bold text-none"
      >
        <template v-if="item.icon === 'customPay'">
          <span class="custom-icon mr-2 ml-n1"  v-html="c_pay_tab_icon">
          </span>
        </template>
        <template v-else-if="item.icon === 'customLeave'">
          <span class="custom-icon mr-2 ml-n1" v-html="c_leave_tab_icon">
          </span>
        </template>
        <template v-else>
          <VIcon
            size="20"
            start
            :icon="item.icon"
          />
        </template>
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
