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
            <span class="font-weight-medium me-1">Full Name:</span>
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
            <span class="font-weight-medium me-1">Role:</span>
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
            <span class="font-weight-medium me-1">Manager:</span>
            <span v-if="props.data.manager"> {{ props.data.manager.first_name + " " + props.data.manager.last_name }}</span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <svg
              id="body_1"
              xmlns="http://www.w3.org/2000/svg"
              xmlns:xlink="http://www.w3.org/1999/xlink"
              width="24"
              height="24"
            >

              <g transform="matrix(0.4528302 0 0 0.4528302 0 0)">
                <path
                  d="M11 4L23 4L24 8L25.934 7.965L28.438 7.938L30.934 7.9030004C 33 8 33 8 34 9C 34.07257 11.520435 34.093582 14.041706 34.063 16.563L34.063 16.563L34.049 18.717L34 24L37.875 23.938C 41.75 23.875 41.75 23.875 44 25L44 25L44 28L35 29L34.852 32.246L34.625 36.436996L34.531 38.579998L34 44C 30.785 45.607 27.564 45.057 24 45L24 45L23 49L11 49L11 36L23 36L25 40L30 41L30 28L25 29L23 33L11 33L11 20L23 20L24 24L30 24L29 13L25 13L23 17L11 17L11 4zM15 8L15 13L20 13L20 8L15 8zM15 24L15 29L20 29L20 24L15 24zM15 40L15 45L18 46L20 45L20 40L15 40z"
                  stroke="none"
                  fill="#8D919F"
                  fill-rule="nonzero"
                />
                <path
                  d="M23 27L31 27L31 42L23 42L22 44L32 44L32 45L24 45L23 49L11 49L11 36L23 36L25 40L30 41L30 28L25 29L23 32L23 27zM15 40L15 45L18 46L20 45L20 40L15 40z"
                  stroke="none"
                  fill="#A8AAB5"
                  fill-rule="nonzero"
                />
                <path
                  d="M29 12L31 12L31 25L22 25L21 31L14 31L13 23L12 32L23 32L23 33L11 33L11 20L23 20L24 24L30 24L29 12zM15 24L15 29L20 29L20 24L15 24z"
                  stroke="none"
                  fill="#A4A6B2"
                  fill-rule="nonzero"
                />
                <path
                  d="M34.723 24.902L38.063 24.937L41.411 24.964L44 25L44 28L35 29L34 44L22 44L23 40L23 42L31 42L30.965 38.535L30.938 34.062L30.913 31.775C 30.91156 29.849915 30.940567 27.924171 31 26C 32 25 32 25 34.723 24.902z"
                  stroke="none"
                  fill="#8C909E"
                  fill-rule="nonzero"
                />
                <path
                  d="M11 4L23 4L24 8L33 8L33 9L22 9L22 14L14 14L13 8L12 16L23 16L23 17L11 17L11 4zM15 8L15 13L20 13L20 8L15 8z"
                  stroke="none"
                  fill="#A2A5B1"
                  fill-rule="nonzero"
                />
                <path
                  d="M12 37L22 37L22 39L14 39L14 46L22 46L22 48L12 48L12 37z"
                  stroke="none"
                  fill="#838796"
                  fill-rule="nonzero"
                />
                <path
                  d="M13 7L14 7L14 14L22 14L22 16L12 16L12 8L13 7z"
                  stroke="none"
                  fill="#828696"
                  fill-rule="nonzero"
                />
              </g>
            </svg>
            <!--
              <VIcon
              icon="tabler-binary-tree-2"
              size="20"
              class="me-2"
              /> 
            -->
          </template>
          <VListItemTitle>
            <span class="font-weight-medium me-1 ml-1">Division:</span>
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
          <span class="font-weight-medium me-1">Group:</span>
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
            <span class="font-weight-medium me-1">Work Phone:</span>
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
            <span class="font-weight-medium me-1">Work Email:</span>
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
            <span class="font-weight-medium me-1">Location:</span>
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
      <VDivider class="mt-8" />
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
              class="font-weight-medium me-1"
            >Password:</span>
            <span
              v-if="edit_password"
              class="font-weight-medium me-1"
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
              class="font-weight-medium me-1"
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
