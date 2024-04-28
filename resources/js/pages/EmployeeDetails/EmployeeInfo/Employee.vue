<script setup>

import { useEmployeeStore } from "@/store/employeeStore";
import { useLocationStore } from "@/store/locationStore";
import { toast } from 'vue3-toastify';
const employeeStore = useEmployeeStore();
const locationStore = useLocationStore();

const props = defineProps({
  data: {
    type: null,
    required: true,
  },
})

let edit_fields = ref(false)
const updateRow = async (row, data)=>{

var update = await employeeStore.updateRow({
    id: props.data.id,
    data,
    row
  })
   toast("Updated!")
console.log(update);
}
</script>

<template>
  <VCard class="mb-4">
    <VCardText>
      <p class="text-lg" >
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
            <span> RAN </span>
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
            <span> (manager name)</span>
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
            <span class="font-weight-medium me-1">Division:</span>
            <span>{{ props.data.division.name }} </span>
          </VListItemTitle>
        </VListItem>
        
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
      </VList>
      <br>

      <p class="text-lg d-flex justify-space-between">
        <span>
          <b>Contact</b>
        </span>
        <span class="cursor-pointer" @click="edit_fields = !edit_fields">
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
          <VListItemTitle>
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

    </VCardText>
  </VCard>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 16px;
}
</style>
