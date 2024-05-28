<script setup>
import { useDivisionStore } from "@/store/divisionStore";
import { useEmployeeStore } from "@/store/employeeStore";
import { useEmploymentStore } from "@/store/employmentStore";
import { useLeaveTypeStore } from "@/store/leaveTypeStore";
import { useLocationStore } from "@/store/locationStore";
import { useRoleStore } from "@/store/roleStore";
import ScrollToTop from '@core/components/ScrollToTop.vue';
import { useThemeConfig } from '@core/composable/useThemeConfig';
import { hexToRgb } from '@layouts/utils';
import { useTheme } from 'vuetify';


const {
  syncInitialLoaderTheme,
  syncVuetifyThemeWithTheme: syncConfigThemeWithVuetifyTheme,
  isAppRtl,
  handleSkinChanges,
} = useThemeConfig()

const { global } = useTheme()
const isDataLoad = ref(false)

// ℹ️ Sync current theme with initial loader theme
syncInitialLoaderTheme()
syncConfigThemeWithVuetifyTheme()
handleSkinChanges()

const leaveTypeStore = useLeaveTypeStore();
const employeeStore = useEmployeeStore();
const divisionStore = useDivisionStore();
const roleStore = useRoleStore();
const locationStore = useLocationStore();
const employmentStore = useEmploymentStore();
onMounted( async() => {
  const loggedIn = localStorage.getItem('is_logged_in') ?? false
  if(loggedIn){
  await leaveTypeStore.setLeaveTypes();
  await employeeStore.setEmployees();
  await employeeStore.fetchWidgetData();
  await divisionStore.setDivisions();
  await roleStore.setRoles();
  await locationStore.setLocations();
  await employmentStore.setEmployments();
  
    isDataLoad.value = true
  }
  else
   isDataLoad.value = true
})
</script>

<template>
  <VLocaleProvider :rtl="isAppRtl">
    <!-- ℹ️ This is required to set the background color of active nav link based on currently active global theme's primary -->
    <VApp :style="`--v-global-theme-primary: ${hexToRgb(global.current.value.colors.primary)}`" v-if="isDataLoad">
      <RouterView />
      <ScrollToTop />
    </VApp>
  </VLocaleProvider>
</template>

<style lang="scss">
.v-overlay-container {
  .v-overlay {
    .app-inner-list {
      .v-list {
        .v-list-item--active {
          .v-list-item__content
          {
            color: white;
          }
        }
      }
    }
  }
}
</style>
