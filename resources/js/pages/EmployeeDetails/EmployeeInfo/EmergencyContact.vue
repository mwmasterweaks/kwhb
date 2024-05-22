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


if(props.data.emergency_contact == null)
  props.data.emergency_contact = {}
if(props.data.medical == null)
  props.data.medical = {}

let edit_fields = ref(false)
let edit_fields_med = ref(false)

const updateRow = async (row, data)=>{

  var update = await employeeStore.updateRow({
    id: props.data.id,
    data,
    row,
  })
  toast("Updated!")
  console.log(update)
}

const isFormValid = ref(false)
const refForm = ref()

const onSubmit = () => {
  refForm.value?.validate().then( async({ valid }) => {
    if (valid) {
      var result = await employeeStore.updateEmergencyContact(
        {
          employee_id: props.data.id,
          name: props.data.emergency_contact.name,
          address: props.data.emergency_contact.address,
          phone: props.data.emergency_contact.phone,
          email: props.data.emergency_contact.email,
          relationship: props.data.emergency_contact.relationship,
        },
      )
      if(!result)  toast("Error please try again!")
      else  {
        toast("Updated!")
        edit_fields.value = false
      }
      nextTick(() => {
        refForm.value?.reset()
        refForm.value?.resetValidation()
      })
    }
  })
}


const isFormValidMed = ref(false)
const refFormMed = ref()

const onSubmitMed = () => {
  refFormMed.value?.validate().then( async({ valid }) => {
    if (valid) {
      var result = await employeeStore.updateMedical(
        {
          employee_id: props.data.id,
          allergies: props.data.medical.allergies,
          medication: props.data.medical.medication,
          condition: props.data.medical.condition,
          diagnosis_date: props.data.medical.diagnosis_date,
        },
      )
      if(!result)  toast("Error please try again!")
      else  {
        toast("Updated!")
        edit_fields_med.value = false
      }
      console.log("valid")
      nextTick(() => {
        refForm.value?.reset()
        refForm.value?.resetValidation()
      })
    }
  })
}
</script>

<template>
  <VCard class="mb-4">
    <VCardText>
      <p class="text-lg d-flex justify-space-between">
        <VIcon
          icon="tabler-phone"
          size="30"
          class="me-2"
        />
        <b>Emergency Contact</b>
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
      <VForm
        ref="refForm"
        v-model="isFormValid"
        @submit.prevent="onSubmit"
      >
        <VList class="card-list text-medium-emphasis employee-card">
          <VListItem>
            <template #prepend>
              <VIcon
                size="20"
                class="me-2"
              />
            </template>
            <VListItemTitle>
              <span class="text-label me-1">Full Name:</span>
              <span v-if="!edit_fields"> {{ props.data.emergency_contact.name }}</span>
              <span v-else>
                <AppTextField
                  v-model="props.data.emergency_contact.name"
                  :rules="[requiredValidator]"
                  placeholder="John Doe"
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
              <span class="text-label me-1">Address:</span>
              <span v-if="!edit_fields"> {{ props.data.emergency_contact.address }}</span>
              <span v-else>
                <AppTextField
                  v-model="props.data.emergency_contact.address"
                  :rules="[requiredValidator]"
                  placeholder="Emergency contact address"
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
              <span class="text-label me-1">Phone:</span>
              <span v-if="!edit_fields"> {{ props.data.emergency_contact.phone }}</span>
              <span v-else>
                <AppTextField
                  v-model="props.data.emergency_contact.phone"
                  :rules="[requiredValidator]"
                  placeholder="Emergency contact phone number"
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
              <span class="text-label me-1">Email:</span>
              <span v-if="!edit_fields"> {{ props.data.emergency_contact.email }} </span>
              <span v-else>
                <AppTextField
                  v-model="props.data.emergency_contact.email"
                  :rules="[requiredValidator, emailValidator]"
                  placeholder="Emergency contact Email address"
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
              <span class="text-label me-1">Relationship:</span>
              <span v-if="!edit_fields"> {{ props.data.emergency_contact.relationship }}</span>
              <span v-else>
                <AppTextField
                  v-model="props.data.emergency_contact.relationship"
                  :rules="[requiredValidator]"
                  placeholder="Relationship"
                />
              </span>
            </VListItemTitle>
          </VListItem>
          <VBtn
            v-if="edit_fields"
            type="submit"
            class="me-3"
          >
            Save
          </VBtn>
        </VList>
      </VForm>
    </VCardText>
  </VCard>

  
  <VCard>
    <VCardText>
      <p class="text-lg d-flex justify-space-between">
        <span>
          <VIcon
            icon="tabler-nurse"
            size="35"
            class="me-2"
          />
          <b>Medical</b>
        </span>
        <span
          class="cursor-pointer"
          @click="edit_fields_med = !edit_fields_med"
        >
          <VIcon
            icon="tabler-pencil"
            size="25"
            class="me-2"
          />
        </span>
      </p>
      
      <VForm
        ref="refFormMed"
        v-model="isFormValidMed"
        @submit.prevent="onSubmitMed"
      >
        <VList class="card-list text-medium-emphasis employee-card">
          <VListItem>
            <template #prepend>
              <VIcon
                size="20"
                class="me-2"
              />
            </template>
            <VListItemTitle>
              <span class="text-label me-1">Allergies:</span>
              <span v-if="!edit_fields_med">{{ props.data.medical.allergies }}</span>
              <span v-else>
                <AppTextField
                  v-model="props.data.medical.allergies"
                  :rules="[requiredValidator]"
                  placeholder="Peanuts"
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
              <span class="text-label me-1">Medications:</span>
              <span v-if="!edit_fields_med">{{ props.data.medical.medication }}</span>
              <span v-else>
                <AppTextField
                  v-model="props.data.medical.medication"
                  :rules="[requiredValidator]"
                  placeholder="Epi-pen"
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
              <span class="text-label me-1">Condition:</span>
              <span v-if="!edit_fields_med">{{ props.data.medical.condition }}</span>
              <span v-else>
                <AppTextField
                  v-model="props.data.medical.condition"
                  :rules="[requiredValidator]"
                  placeholder="Anaphylaxis"
                />
              </span>
            </VListItemTitle>
          </VListItem>
          <!--
            <VListItem>
            <template #prepend>
            <VIcon
            size="20"
            class="me-2"
            />
            </template>
            <VListItemTitle>
            <span class="text-label me-1">Diagnosis Date:</span>
            <span v-if="!edit_fields_med"> {{ props.data.medical.diagnosis_date }}</span>
            <span v-else>
            <AppDateTimePicker
            v-model="props.data.medical.diagnosis_date"
            :rules="[requiredValidator]"
            placeholder="Jan 1 2024"
            />
            </span>
            </VListItemTitle>
            </VListItem>
          -->
          <VBtn
            v-if="edit_fields_med"
            type="submit"
            class="me-3"
          >
            Save
          </VBtn>
        </VList>
      </VForm>
    </VCardText>
  </VCard>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 16px;
}
</style>
