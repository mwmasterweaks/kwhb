<template>
  <div>
    <VCard
      class="mb-6"
      title="Dashboard"
    >
    </VCard>
    <VBtn @click="myob_clicked">
      myob test
    </VBtn>
  </div>
</template>

<script setup>
import myob_emp from '@/api/employee/myob_employee';
// const { setEmployees } = useEmployeeStore();
//   setEmployees();
const myob_clicked  = async() => {
  console.log("asdf");
  const test = await myob_emp.fetchEmployees();
  console.log(test);
}
</script>
