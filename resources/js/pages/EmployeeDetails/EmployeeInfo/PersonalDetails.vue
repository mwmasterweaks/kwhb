<script setup>
import { useEmployeeStore } from "@/store/employeeStore"
import { toast } from 'vue3-toastify'

const props = defineProps({
  data: {
    type: null,
    required: true,
  },
})

const employeeStore = useEmployeeStore()

let edit_fields = ref(false)
let address = ref('')

const updateRow = async (row, data)=>{
  var update = await employeeStore.updateRow({
    id: props.data.id,
    data,
    row,
  })
  toast("Updated!")
  console.log(update)
}

const updateAddress = async ()=>{
  const param = {
    employee_id: props.data.id,
    name: "Employee details address",
    label: "home",
    address: address.value,
    is_default: true,
    is_active: true,
  }

  var result = await employeeStore.updateAddress(param)
  
  console.log(result)
  if(!result)  toast("Error please try again!")
  else  {
    toast("Updated!")
    edit_fields.value = false
  }
}

onMounted(() => {
  //console.log(props.data);
  if (props.data.address.length > 0) {
    address.value = props.data.address[0].address
  } 
})

const age = computed(() => {
  const dob = props.data.dob
  if (!dob) return '' // Handle case where DOB is not provided

  const dobDate = new Date(dob)
  const currentDate = new Date()
  const ageDiff = currentDate.getFullYear() - dobDate.getFullYear()

  // Adjust age if birthday hasn't occurred yet this year
  if (
    currentDate.getMonth() < dobDate.getMonth() ||
    (currentDate.getMonth() === dobDate.getMonth() && currentDate.getDate() < dobDate.getDate())
  ) {
    return ageDiff - 1
  }

  return ageDiff
})
</script>

<template>
  <VCard class="mb-4">
    <VCardText>
      <p class="text-lg d-flex justify-space-between">
        <span>
          <VIcon
            icon="tabler-user"
            size="30"
            class="me-2"
          />
          <b>Personal Details</b>
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
              icon="tabler-minus-vertical"
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="font-weight-medium me-1">Full Name:</span>
            <span v-if="!edit_fields">{{ props.data.first_name }} {{ props.data.last_name }}</span>
            <span v-else>
              <AppTextField
                v-model="props.data.first_name"
                :rules="[requiredValidator]"
                placeholder="John"
                @keyup.enter="updateRow('first_name', props.data.first_name)"
              />
              <AppTextField
                v-model="props.data.last_name"
                :rules="[requiredValidator]"
                placeholder="Doe"
                @keyup.enter="updateRow('last_name', props.data.last_name)"
              />
            </span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon
              icon="tabler-minus-vertical"
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="font-weight-medium me-1">Gender:</span>
            <span v-if="!edit_fields"> {{ props.data.gender }}  </span>
            <span v-else>
              <AppSelect
                v-model="props.data.gender"
                placeholder="Select Gender"
                :items="['Male', 'Female']"
                @update:modelValue="updateRow('gender', props.data.gender)"
              />
            </span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon
              icon="tabler-minus-vertical"
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="font-weight-medium me-1">Pronouns:</span>
            <span v-if="!edit_fields">  {{ props.data.pronouns }} </span>
            <span v-else>
              <AppTextField
                v-model="props.data.pronouns"
                placeholder="pronouns"
                @keyup.enter="updateRow('pronouns', props.data.pronouns)"
              />
            </span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon
              icon="tabler-minus-vertical"
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="font-weight-medium me-1">Indigenous:</span>
            <span v-if="!edit_fields">{{ props.data.indigenous }} </span>
            <span v-else>
              <AppTextField
                v-model="props.data.indigenous"
                placeholder="indigenous"
                @keyup.enter="updateRow('indigenous', props.data.indigenous)"
              />
            </span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon
              icon="tabler-minus-vertical"
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="font-weight-medium me-1">DOB:</span>
            <span v-if="!edit_fields">{{ props.data.dob }} </span>
            <span v-else>
              <AppDateTimePicker
                v-model="props.data.dob"
                placeholder="Select Date of birth"
                @change="updateRow('dob', props.data.dob)"
              />
            </span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon
              icon="tabler-minus-vertical"
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="font-weight-medium me-1">Age:</span>
            <span>{{ age }} </span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon
              icon="tabler-minus-vertical"
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="font-weight-medium me-1">Address:</span>
            <span v-if="!edit_fields"> <p style="white-space: pre-wrap;">{{ address }}</p> </span>
            <span v-else>
              
              <Autocomplete 
                v-model="address"
                @update:modelValue="updateAddress" 
              />
              <!--
                <AppTextField
                v-model="props.data.address"
                :rules="[requiredValidator]"
                placeholder="address"
                @keyup.enter="updateRow('address', props.data.address)"
                /> 
              -->
            </span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon
              icon="tabler-minus-vertical"
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="font-weight-medium me-1">Personal Phone:</span>
            <span v-if="!edit_fields">{{ props.data.personal_phone }} </span>
            <span v-else>
              <AppTextField
                v-model="props.data.personal_phone"
                :rules="[requiredValidator]"
                placeholder="personal_phone"
                @keyup.enter="updateRow('personal_phone', props.data.personal_phone)"
              />
            </span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon
              icon="tabler-minus-vertical"
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="font-weight-medium me-1">Personal Email:</span>
            <span v-if="!edit_fields">{{ props.data.personal_email }} </span>
            <span v-else>
              <AppTextField
                v-model="props.data.personal_email"
                :rules="[requiredValidator]"
                placeholder="personal_email"
                @keyup.enter="updateRow('personal_email', props.data.personal_email)"
              />
            </span>
          </VListItemTitle>
        </VListItem>
      </VList>
    </VCardText>
  </VCard>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 16px;
}
</style>
