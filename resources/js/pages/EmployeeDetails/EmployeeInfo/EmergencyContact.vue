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
          <svg
            id="svg1"
            width="24"
            height="24"
            viewBox="0 0 192.52462 192.52861"
            version="1.1"
            inkscape:export-filename="kwhb_employee_division.svg"
            inkscape:export-xdpi="96"
            inkscape:export-ydpi="96"
            xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
            xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:svg="http://www.w3.org/2000/svg"
            class="me-2"
          >
            <sodipodi:namedview
              id="namedview1"
              pagecolor="#ffffff"
              bordercolor="#000000"
              borderopacity="0.25"
              inkscape:showpageshadow="2"
              inkscape:pageopacity="0.0"
              inkscape:pagecheckerboard="0"
              inkscape:deskcolor="#d1d1d1"
              inkscape:document-units="mm"
            />
            <defs id="defs1" />
            <g
              id="layer1"
              inkscape:label="Layer 1"
              inkscape:groupmode="layer"
              transform="translate(-13.28609,-43.29803)"
            >
              <path
                id="path95"
                d="m 197.08124,121.33181 v -8.73114 H 136.50281 V 52.02884 c 0,-2.2935 -0.9273,-4.54014 -2.5542,-6.17001 -1.6236,-1.6302 -3.87453,-2.5608 -6.17595,-2.5608 H 91.31888 c -2.3001,0 -4.54707,0.9306 -6.17595,2.5608 -1.6236,1.6302 -2.5542,3.87684 -2.5542,6.17001 v 60.57183 H 22.01558 c -2.3001,0 -4.5474,0.9306 -6.17529,2.5608 -1.6269,1.617 -2.5542,3.86496 -2.5542,6.17001 v 36.4617 c 0,2.2935 0.9273,4.54014 2.5542,6.17001 1.6269,1.6302 3.8742,2.5608 6.17529,2.5608 h 60.57315 v 60.5715 c 0,2.2935 0.9339,4.55202 2.5542,6.17034 1.6269,1.6302 3.87453,2.5608 6.17595,2.5608 h 36.45246 c 2.3001,0 4.55301,-0.9306 6.17595,-2.5608 1.6302,-1.617 2.5542,-3.87684 2.5542,-6.17034 v -60.5715 h 60.57843 c 2.2968,0 4.5474,-0.9306 6.17001,-2.5608 1.6302,-1.6302 2.5608,-3.87651 2.5608,-6.17001 v -36.4617 c 0,-2.3067 -0.9339,-4.55202 -2.5608,-6.17001 -1.6236,-1.6302 -3.8742,-2.5608 -6.17001,-2.5608 v 8.73114 h -8.73114 v 27.73056 h -60.57876 c -2.2968,0 -4.54707,0.9306 -6.17001,2.5476 -1.6302,1.6302 -2.5608,3.87684 -2.5608,6.18189 v 60.5715 h -18.98985 v -60.5715 c 0,-2.3067 -0.9339,-4.55202 -2.5608,-6.18189 -1.6236,-1.617 -3.8742,-2.5476 -6.17133,-2.5476 H 30.74441 v -18.99942 h 60.57282 c 2.2968,0 4.5474,-0.9306 6.17133,-2.5608 1.6269,-1.617 2.5608,-3.87651 2.5608,-6.17001 v -60.5715 h 18.98985 v 60.5715 c 0,2.2935 0.9339,4.55169 2.5608,6.17001 1.6236,1.6302 3.87453,2.5608 6.17001,2.5608 h 69.3099 v -8.73114 h -8.73114 8.73114"
                style="fill: #726f7b;fill-opacity: 1;fill-rule: nonzero;stroke: none;stroke-width: 1.16416;"
                inkscape:export-filename="path95.svg"
                inkscape:export-xdpi="96"
                inkscape:export-ydpi="96"
              />
            </g>
          </svg>

          <!--
            <VIcon
            icon="tabler-nurse"
            size="35"
            class="me-2"
            /> 
          -->
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
