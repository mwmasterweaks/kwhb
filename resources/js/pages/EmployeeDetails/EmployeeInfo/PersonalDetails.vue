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
  if(row == 'dob') {
    const [day, month, year] = data.split('/');
    data = `${year}-${month}-${day}`;
  }
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

const parseDate = (dateString) => {
  // If the date is in the format 'YYYY-MM-DD'
  if (dateString.includes('-')) {
    return new Date(dateString);
  }

  // If the date is in the format 'd/m/Y'
  const [day, month, year] = dateString.split('/');
  return new Date(`${year}-${month}-${day}`);
};
onMounted(() => {
  //console.log(props.data);
  if (props.data.address.length > 0) {
    address.value = props.data.address[0].address
  } 
})

const age = computed(() => {
  console.log("props.data.dob", props.data.dob);
  const dob = props.data.dob
  if (!dob) return '' // Handle case where DOB is not provided

  const dobDate = parseDate(dob);

  if (isNaN(dobDate)) return '';
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
      <VList class="card-list text-medium-emphasis employee-card">
        <VListItem>
          <template #prepend>
            <VIcon
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="me-1 text-label">Full Name:</span>
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
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="me-1 text-label">Gender:</span>
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
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="me-1 text-label">Pronouns:</span>
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
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="me-1 text-label">Indigenous:</span>
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
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="me-1 text-label">DOB:</span>
            <span v-if="!edit_fields">{{ $formatDateDMY(props.data.dob) }} </span>
            <span v-else>
              <AppDateTimePicker
                v-model="props.data.dob"
                placeholder="Select Date of birth"
                @change="updateRow('dob', props.data.dob)"
                :config="{ dateFormat: 'd/m/Y' }"
              />
            </span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="me-1 text-label">Age:</span>
            <span>{{ age }} </span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="me-1 text-label">Address:</span>
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
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="me-1 text-label">Personal Phone:</span>
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
              size="20"
              class="me-2"
            />
          </template>
          <VListItemTitle>
            <span class="me-1 text-label">Personal Email:</span>
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

<style lang="scss">
.card-list {
  --v-card-list-gap: 16px;
}
.text-label {
  font-weight: 900;
  color: black !important;

}
.employee-card {
  border-left: 2px solid silver; border-radius: 0px; margin-left: 15px;
}
.employee-card > div {
 margin-left: -15px;
}
</style>
