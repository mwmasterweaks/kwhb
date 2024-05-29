<script setup>

import router from '@/router';
import { useAuthStore } from "@/store/authStore";
import { useDivisionStore } from "@/store/divisionStore";
import { useEmployeeStore } from "@/store/employeeStore";
import { useEmploymentStore } from "@/store/employmentStore";
import { useLeaveTypeStore } from "@/store/leaveTypeStore";
import { useLocationStore } from "@/store/locationStore";
import { useRoleStore } from "@/store/roleStore";
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant';
import bg_img from '@images/pages/admin_login.png';
import authV2LoginIllustrationBorderedDark from '@images/pages/auth-v2-login-illustration-bordered-dark.png';
import authV2LoginIllustrationBorderedLight from '@images/pages/auth-v2-login-illustration-bordered-light.png';
import authV2LoginIllustrationDark from '@images/pages/auth-v2-login-illustration-dark.png';
import authV2LoginIllustrationLight from '@images/pages/auth-v2-login-illustration-light.png';
import authV2MaskDark from '@images/pages/misc-mask-dark.png';
import authV2MaskLight from '@images/pages/misc-mask-light.png';
import { VNodeRenderer } from '@layouts/components/VNodeRenderer';

import { toast } from 'vue3-toastify';
import { VForm } from 'vuetify/components/VForm';



const leaveTypeStore = useLeaveTypeStore();
const employeeStore = useEmployeeStore();
const divisionStore = useDivisionStore();
const roleStore = useRoleStore();
const locationStore = useLocationStore();
const employmentStore = useEmploymentStore();

const authStore = useAuthStore();

const authThemeImg = useGenerateImageVariant(authV2LoginIllustrationLight, authV2LoginIllustrationDark, authV2LoginIllustrationBorderedLight, authV2LoginIllustrationBorderedDark, true)
const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark)
const isPasswordVisible = ref(false)
const refVForm = ref()
const username = ref('')
const password = ref('')
const rememberMe = ref(false)

const onSubmit = async() => {
  debugger
  const res = await authStore.login({username: username.value, password: password.value})
  console.log("authStore.is_logged_in", authStore.is_logged_in);
  if(!authStore.is_logged_in){
    toast(res)
  }
  else {
    await leaveTypeStore.setLeaveTypes();
    await employeeStore.setEmployees();
    await employeeStore.fetchWidgetData();
    await divisionStore.setDivisions();
    await roleStore.setRoles();
    await locationStore.setLocations();
    await employmentStore.setEmployments();
    router.push("employee")
  }

}
</script>

<style lang="scss" scoped>
.bg_img {
  width: 100%;
  height: 100%;
}

.welcome-title {
  color:#001871
}
</style>
<template>
 
  <VRow style=" height: 80%;">
    <VCol
      cols="6"
      style="  height: 100%;"
      lg="6"
    >
      <VImg
      :src="bg_img"
      style=""
      class="bg_img"
    /> 
    </VCol>

    <VCol
      cols="6"
      lg="4"
      style=""
      class="auth-card-v2 d-flex align-center justify-center"
    >
      <VCard
        flat
        :max-width="700"
        width="600px"
        class=""
      >
        <VCardText>
          <VNodeRenderer
            class="mb-6"
          />
          <center><h2 class="welcome-title">Welcome to Future You Workforce Management</h2>
          <h3 class="align-center">Please sign in</h3></center>
        </VCardText>

      

        <VCardText>
          <VForm
            ref="refVForm"
            @submit.prevent="onSubmit"
          >
            <VRow>
              <!-- email -->
              <VCol cols="12">
                <AppTextField
                  v-model="username"
                  label="Username"
                  type="text"
                  autofocus
                />
              </VCol>

              <!-- password -->
              <VCol cols="12">
                <AppTextField
                  v-model="password"
                  label="Password"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />

                <div class="d-flex align-center flex-wrap justify-space-between mt-2 mb-4">
                  <VCheckbox
                    v-model="rememberMe"
                    label="Remember me"
                  />
                  <a
                    class="text-primary ms-2 mb-1"
                    href="#"
                  >
                    Forgot Password?
                  </a>
                </div>

                <VBtn
                  block
                  type="submit"
                >
                  Sign in
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";
</style>

<route lang="yaml">
meta:
  layout: blank
</route>
