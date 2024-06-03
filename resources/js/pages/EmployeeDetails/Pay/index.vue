<script setup>
import { useEmployeeStore } from "@/store/employeeStore";
import { toast } from 'vue3-toastify';
import AddNewBankDrawer from "./AddNewBankDrawer.vue";
import SuperInformation from './SuperInformation.vue';


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
    
      <VCard class="mb-4 bank-info-card">
        <VCardText>
          <p class="text-lg d-flex justify-space-between">
            <span>
              <b>Bank Information</b>
            </span>
            <span class="cursor-pointer">
              <VBtn
                variant="outlined"
                size="large"
                color="secondary"
                @click="isAddNewBankDrawerVisible = true"
              >
                Add Bank
              </VBtn>
            </span>
          </p>

          <div class="pa-2 pay-theme-background rounded"
            style="margin-top: 12px;"
            v-for="item in employeeStore.data.employee_selected.bank_info"
            :key="item.id"
          >
            <VRow>
              <VCol md="7" cols="12">
                <p class="text-lg mt-4">
                  {{ item.account_name }}
                  <!-- {{ profileTabData.first_name }} {{ profileTabData.last_name }}  -->
                  <VChip
                  class="pa-2 ml-3"
                    label
                    v-if="item.primary"
                    color="success"
                    size="large"
                  >
                    Primary
                  </VChip>
                </p>
                <table>
                  <tr>
                  <td style="padding: 10px;">
                    <b>BSB: </b> 
                    <span v-if="!item.edit_fields"> {{ item.bsb }} </span>
                    <span v-else>
                      <AppTextField
                          v-model="item.bsb"
                          :rules="[requiredValidator]"
                          placeholder="123-234"
                        />
                    </span>
                  </td>
                  <td>
                    <b>Account: </b>
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
                  </td>
                  </tr>
                </table>
               
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
              <VCol md="5" cols="12">
                <p class="text-right mt-4 mr 2" >
                  <VBtn 
                    style="margin-right: 10px; padding: 5px;" 
                    @click="item.edit_fields = true"
                    color="kwhb"
                    variant="tonal"
                    v-if="!item.edit_fields"><b>Edit</b>
                    </VBtn>
                  <VBtn 
                    style="margin-left: 2px; padding: 5px;"  
                    color="kwhb"
                    variant="tonal"
                    @click="item.edit_fields = false"
                    v-else>Cancel
                  </VBtn>
                  <VBtn style="margin-left: 2px; padding: 5px;"
                    color="kwhb-waring"
                  >Delete</VBtn>
                </p>
                <p class="d-flex justify-space-between">
                  <AppSelect
                    :disabled="!item.edit_fields"
                    style="margin-left: 5px; background-color: white;"
                    v-model="item.pay_split"
                    label="Pay Split"
                    placeholder="Percentage %"
                    :items="['Percentage', 'Whole Number']"
                  />
                  <AppTextField
                    :disabled="!item.edit_fields"
                    v-model="item.pay_split_value "
                    style="margin-left: 5px; background-color: white;"
                    label="Enter Value"
                    :placeholder="item.pay_split == 'Whole Number'? '$0': '0%'"
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
      <SuperInformation :data="profileTabData" />
    </VCol>
  </VRow> 
</template>
<style lang="scss">
.pay-theme-background {
  background-color: #F8F8F9;
}
.bank-info-card {
  font-family: Arial, Helvetica, sans-serif;
  color: #383E59;
}
</style>
