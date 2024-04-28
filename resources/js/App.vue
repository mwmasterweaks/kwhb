<script setup>
import { useDivisionStore } from "@/store/divisionStore";
import { useEmployeeStore } from "@/store/employeeStore";
import { useEmploymentStore } from "@/store/employmentStore";
import { useLeaveTypeStore } from "@/store/leaveTypeStore";
import { useLocationStore } from "@/store/locationStore";
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

// ℹ️ Sync current theme with initial loader theme
syncInitialLoaderTheme()
syncConfigThemeWithVuetifyTheme()
handleSkinChanges()

const leaveTypeStore = useLeaveTypeStore();
const employeeStore = useEmployeeStore();
const divisionStore = useDivisionStore();
const locationStore = useLocationStore();
const employmentStore = useEmploymentStore();
onMounted( async() => {
  const loggedIn = localStorage.getItem('is_logged_in') ?? false
  if(loggedIn){
  await leaveTypeStore.setLeaveTypes();
  await employeeStore.setEmployees();
  await divisionStore.setDivisions();
  await locationStore.setLocations();
  await employmentStore.setEmployments();}
})
</script>

<template>
  <VLocaleProvider :rtl="isAppRtl">
    <!-- ℹ️ This is required to set the background color of active nav link based on currently active global theme's primary -->
    <VApp :style="`--v-global-theme-primary: ${hexToRgb(global.current.value.colors.primary)}`">
      <RouterView />
      <ScrollToTop />
    </VApp>
  </VLocaleProvider>
</template>
