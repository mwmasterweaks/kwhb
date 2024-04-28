<script setup>
import { useEmployeeStore } from "@/store/employeeStore";
import { toast } from 'vue3-toastify';
import AddNewBankDrawer from "./AddNewBankDrawer.vue";
import TaxFileNumber from './TaxFileNumber.vue';


const employeeStore = useEmployeeStore();
const profileTabData = ref()
if(employeeStore.data.employee_selected.bank_info == null)
employeeStore.data.employee_selected.bank_info = []
profileTabData.value = employeeStore.data.employee_selected

const isAddNewBankDrawerVisible = ref(false)
const addNewBankInfo = async data => {
    
    console.log("data", data);
    var result = await employeeStore.addBankInfo(data)
    if(!result) toast("Error please try again!")
    else {
      toast("Added!")
    }
  }
const updateBankInfo = async (item) => {
    
    console.log("updateBankInfo");
    var result = await employeeStore.updateBankInfo(item)
    if(!result)  toast("Error please try again!")
      else  {
        toast("Updated!")
        item.edit_fields = false
      }
  }

</script>

<template>
  <AddNewBankDrawer
    v-model:isDrawerOpen="isAddNewBankDrawerVisible"
    @user-data="addNewBankInfo"
  />
  <VRow v-if="profileTabData">
    <VCol md="6" cols="12">
    
      <VCard class="mb-4">
        <VCardText>
          <p class="text-lg d-flex justify-space-between">
            <span>
              <b>Bank Information</b>
            </span>
            <span class="cursor-pointer">
              <VBtn
                prepend-icon="tabler-plus"
                variant="outlined"
                size="large"
                color="secondary"
                @click="isAddNewBankDrawerVisible = true"
              >
                Add Bank
              </VBtn>
            </span>
          </p>

          <div class="pa-2 bg-var-theme-background rounded"
            style="margin-top: 12px;"
            v-for="item in employeeStore.data.employee_selected.bank_info"
            :key="item.id"
          >
            <VRow>
              <VCol md="6" cols="12">
                <p class="text-lg">
                  {{ profileTabData.first_name }} {{ profileTabData.last_name }} 
                  <VChip
                    label
                    v-if="item.primary"
                    color="success"
                    size="small"
                  >
                    Primary
                  </VChip>
                </p>
                <p class="d-flex flex-wrap">
                  <span class="flex-1-0">
                    <b>BSB:</b> 
                    <span v-if="!item.edit_fields">{{ item.bsb }} </span>
                    <span v-else>
                      <AppTextField
                          v-model="item.bsb"
                          :rules="[requiredValidator]"
                          placeholder="123-234"
                        />
                    </span>
                  </span>
                  <span class="flex-1-0">
                    <b>Account:</b>
                    <span v-if="!item.edit_fields">
                      {{ item.account }}
                    </span>
                    <span v-else>
                      <AppTextField
                          v-model="item.account"
                          :rules="[requiredValidator]"
                          placeholder="0864 54343"
                        />
                    </span>
                  </span>

                </p>
               
                <span>
                  <VCheckbox
                    v-model="item.reimbursement"
                    label="Reimbursement Account"
                    :true-value="1"
                    :false-value="0"
                  />
                </span>
                <span  v-if="item.edit_fields">
                  <VCheckbox
                    v-model="item.primary"
                    label="Primary"
                    :true-value="1"
                    :false-value="0"
                  />
                </span>
                <span  v-if="item.edit_fields">
                  <VBtn
                    @click="updateBankInfo(item)"
                  >
                    Save
                  </VBtn>
                </span>
              </VCol>
              <VCol md="6" cols="12">
                <p>
                  <VBtn 
                    style="margin-left: 3px;" 
                    @click="item.edit_fields = true"
                    color="kwhb"
                    variant="tonal"
                    v-if="!item.edit_fields">Edit
                    </VBtn>
                  <VBtn 
                    style="margin-left: 3px;" 
                    color="kwhb"
                    variant="tonal"
                    @click="item.edit_fields = false"
                    v-else>Cancel
                  </VBtn>
                  <VBtn style="margin-left: 3px;"
                    color="warning"
                    variant="tonal"
                  >Delete</VBtn>
                </p>
                <p class="d-flex justify-space-between">
                  <AppSelect
                    style="margin-left: 5px;"
                    v-model="item.pay_split"
                    label="Pay Split"
                    placeholder="Percentage %"
                    :items="['Percentage', 'Whole Number']"
                  />
                  <AppTextField
                    v-model="item.pay_split_value"
                    style="margin-left: 5px;"
                    label="Enter Value"
                    placeholder="0%"
                  />
                </p>
              </VCol>
            </VRow>
            
          </div>
        </VCardText>
      </VCard>
    </VCol>

    <VCol
      cols="12"
      md="6"
    >
      <TaxFileNumber :data="profileTabData" />
    </VCol>
  </VRow> 
</template>
