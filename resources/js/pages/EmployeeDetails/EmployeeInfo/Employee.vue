<script setup>
import { useEmployeeStore } from "@/store/employeeStore"
import { useLocationStore } from "@/store/locationStore"
import { toast } from 'vue3-toastify'

const props = defineProps({
  data: {
    type: null,
    required: true,
  },
})

const employeeStore = useEmployeeStore()
const locationStore = useLocationStore()
const isPasswordVisible = ref(false)
const isPasswordVisible2 = ref(false)

let edit_fields = ref(false)
let edit_password = ref(false)

const updateRow = async (row, data)=>{

  var update = await employeeStore.updateRow({
    id: props.data.id,
    data,
    row,
  })
  toast("Updated!")
  console.log(update)
}
</script>

<template>
  <VCard class="mb-4">
    <VCardText>
      <p class="text-lg">
        <b>Employee</b>
      </p>
      <VList class="card-list text-medium-emphasis">
        <VListItem>
          <template #prepend>
            <VIcon
              icon="tabler-user"
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="text-label me-1">Full Name:</span>
            <span>{{ props.data.first_name }} {{ props.data.last_name }}</span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon
              icon="tabler-crown"
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="text-label me-1">Role:</span>
            <span v-if="props.data.user"> {{ props.data.user.roles[0].name }} </span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon
              icon="tabler-user"
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="text-label me-1">Manager:</span>
            <span v-if="props.data.manager"> {{ props.data.manager.first_name + " " + props.data.manager.last_name }}</span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon
              icon="tabler-binary-tree-2"
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="text-label me-1">Division:</span>
            <span v-if="props.data.division">{{ props.data.division.name }} </span>
          </VListItemTitle>
        </VListItem>
        
        <!--
          <VListItem>
          <template #prepend>
          <VIcon
          icon="tabler-color-filter"
          size="20"
          class="me-2"
          />
          </template>
          
          <VListItemTitle>
          <span class="text-label me-1">Group:</span>
          <span>(group name)</span>
          </VListItemTitle>
          
          </VListItem>
        -->
      </VList>
      <br>

      <p class="text-lg d-flex justify-space-between">
        <span>
          <b>Contact</b>
        </span>
        <span
          class="cursor-pointer"
          @click="edit_fields = !edit_fields"
        >
          <VIcon
            icon="tabler-pencil"
            size="25"
            class="me-2"
          />
        </span>
      </p>
      
      <VList class="card-list text-medium-emphasis">
        <VListItem>
          <template #prepend>
            <VIcon
              icon="tabler-phone-call"
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="text-label me-1">Work Phone:</span>
            <span v-if="!edit_fields">{{ props.data.work_phone }}</span>
            <span v-else>
              <AppTextField
                v-model="props.data.work_phone"
                :rules="[requiredValidator]"
                @keyup.enter="updateRow('work_phone', props.data.work_phone)"
              />
            </span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon
              icon="tabler-mail"
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="text-label me-1">Work Email:</span>
            <span v-if="!edit_fields">{{ props.data.work_email }}</span>
            <span v-else>
              <AppTextField
                v-model="props.data.work_email"
                :rules="[requiredValidator]"
                @keyup.enter="updateRow('work_email', props.data.work_email)"
              />
            </span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon
              icon="tabler-map-pin"
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle v-if="props.data.location">
            <span class="text-label me-1">Location:</span>
            <span v-if="!edit_fields">{{ props.data.location.name }}</span>
            <AppSelect
              v-else
              v-model="props.data.location_id"
              placeholder="Select Location"
              item-title="name"
              item-value="id"
              :rules="[requiredValidator]"
              :items="locationStore.data.locations"
              @update:modelValue="updateRow('location_id', props.data.location_id)"
            />
          </VListItemTitle>
        </VListItem>
      </VList>
      <br>
      <br>
      <VList class="card-list text-medium-emphasis mt-4">
        <VListItem>
          <template #prepend>
            <VIcon
              icon="tabler-lock"
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span
              v-if="!edit_password"
              class="text-label me-1"
            >Password:</span>
            <span
              v-if="edit_password"
              class="text-label me-1"
            >New Password:</span>
            <span v-if="!edit_password">********</span>
            <AppTextField
              v-else
              v-model="props.data.password1"
              :type="isPasswordVisible2 ? 'text' : 'password'"
              :append-inner-icon="isPasswordVisible2 ? 'tabler-eye-off' : 'tabler-eye'"
              @click:append-inner="isPasswordVisible2 = !isPasswordVisible2"
            />
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon
              v-if="edit_password"
              icon="tabler-lock"
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span
              v-if="edit_password"
              class="text-label me-1"
            >Re-enter Password:</span>
            <AppTextField
              v-if="edit_password"
              v-model="props.data.password"
              :type="isPasswordVisible ? 'text' : 'password'"
              :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
              @click:append-inner="isPasswordVisible = !isPasswordVisible"
              @keyup.enter="updateRow('password', props.data.password)"
            />
          </VListItemTitle>
        </VListItem>
      </VList>
      <div
        v-if="props.data.password1 != props.data.password"
        style="color: red; font-size: small;"
      >
        Passwords do not match!
      </div>
      <VBtn
        v-if="!edit_password"
        color="secondary"
        variant="outlined"
        class="mt-1 mr-3"
        @click="edit_password = !edit_password"
      >
        Reset Password
      </VBtn>
      <VBtn
        v-if="edit_password"
        color="secondary"
        variant="outlined"
        class="mt-4 mr-3"
        @click="edit_password = !edit_password"
      >
        Cancel
      </VBtn>
      <VBtn
        v-if="edit_password"
        color="primary"
        variant="tonal"
        class="mt-4"
        @click="updateRow('password', props.data.password); edit_password = !edit_password"
      >
        Save
      </VBtn>
    </VCardText>
  </VCard>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 16px;
}
</style>
