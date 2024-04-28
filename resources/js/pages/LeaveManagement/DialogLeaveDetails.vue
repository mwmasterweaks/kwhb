<script setup>
import { useEmployeeStore } from "@/store/employeeStore";
import { useLeaveBalanceStore } from "@/store/leaveBalanceStore";
import { useLeaveStore } from "@/store/leaveStore";
import { useLeaveTypeStore } from "@/store/leaveTypeStore";
import { VDataTableServer } from 'vuetify/labs/VDataTable';

const leaveBalanceStore = useLeaveBalanceStore();
const employeeStore = useEmployeeStore();
const profileTabData = ref()
const leaveBalance = ref({})
const leaveDetails = ref([])
const approvers = ref([])
profileTabData.value = employeeStore.data.employee_selected

const leaveTypeStore = useLeaveTypeStore();
const leaveStore = useLeaveStore();

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  leaveDetail: {
    type: Object,
    required: true
  }
})

const emit = defineEmits([
  'update:isDialogVisible'
])



onMounted( async() => {
  console.log(props.leaveDetail);
})

const headers = [
  {
    title: 'Date',
    key: 'leave_date',
  },
  {
    title: 'Day',
    key: 'day_name',
  },
  {
    title: 'Hours',
    key: 'hour',
  },
]
</script>

<template>
 <VDialog
    persistent
    :width="$vuetify.display.smAndDown ? 'auto' : 1100 "
    :model-value="props.isDialogVisible"
    @update:model-value="val => $emit('update:isDialogVisible', val)"
  >
    <!-- 👉 Dialog close btn -->
    <DialogCloseBtn @click="$emit('update:isDialogVisible', false)" />

    <VCard
      class="pa-sm-8 pa-5"
    >
      <VCardItem>
        <VCardTitle class="text-h3 text-center">
         Leave Details
        </VCardTitle>
      </VCardItem>

      <VCardText>
     
      
        <VRow>
          <VCol cols="12" md="6">
            <AppTextField
              v-model="props.leaveDetail.employee.first_name"
              label="Name"
              disabled
            />
          </VCol>
          <VCol cols="12" md="6">
            <AppTextField
              v-model="props.leaveDetail.leave_type.name"
              label="Leave Type"
              disabled
            />
          </VCol>
        </VRow>
        <VRow>
          <VCol cols="12" md="6">
            <AppTextField
              v-model="props.leaveDetail.date_from"
              label="Start Date"
              disabled
            />
          </VCol>
          <VCol cols="12" md="6">
            <AppTextField
              v-model="props.leaveDetail.date_to"
              label="End Date"
              disabled
            />
          </VCol>
        </VRow>
    
        <VRow>
          <VCol cols="12" md="12" >
            <AppTextarea
            v-model="props.leaveDetail.reason"
            label="Reason for Leave"
            placeholder="Type Reason here..."
            auto-grow
            disabled
          />
          </VCol>
        </VRow>
         <VRow>
           <VCol cols="12" md="12" >
            Attachments
            <span v-for="(atch, index) in props.leaveDetail.attachments" :key="index">
                <br>
               <a :href="$attachment_path + 'leave_attachments/' + atch.file_name" target="_blank">
                {{ atch.file_name }}
              </a>
            </span>
           
           </VCol>
        </VRow>
        <VRow>
          <VCol cols="12" md="12" >
            <VCard class="pa-sm-4 pa-5">
              <VCardItem style="padding: 5px">
                  <VCardTitle class="text-h5 text-center">
                  Leave Details
                  </VCardTitle>
                </VCardItem>
                <hr>
                <!-- <p class="text-center">Total Hours Requested: {{ leaveBalance == null ? 0 : leaveBalance.leave_requested }}</p> -->
                <VCardText>
                  <VDataTableServer
                    :headers="headers"
                    :items="props.leaveDetail.leave_details"
                  >
                    <template #bottom></template>
                  </VDataTableServer>

                </VCardText>
              </VCard>
          </VCol>
        </VRow> 
        
        
      </VCardText>
    </VCard>
  </VDialog>
</template>
