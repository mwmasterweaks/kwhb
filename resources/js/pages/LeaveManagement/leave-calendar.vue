


<template>
  <div class="page-content-container">

    <section>
      <VCard
        title=""
        class="mb-6"
      >
         <VCardText class="d-flex flex-wrap py-6 gap-6" style="">
          <div class="me-12 d-flex justify-space-evenly gap-2" width="200" style="">
            <AppTextField
              class="filter-field"
              density="compact"
              v-model="filter.search_name"
              prepend-inner-icon="tabler-search"
              placeholder="Search by Name"
              persistent-placeholder
              @input="filter_change"
            />
           <AppSelect
              class="filter-field"
              density="compact"
              v-model="filter.division"
              placeholder="Division"
              item-title="name"
              item-value="id"
              :items="['all', ...divisionStore.data.divisions]"
              @update:modelValue="filter_change"
            />
            <AppSelect
              class="filter-field"
              density="compact"
              v-model="filter.location"
              placeholder="Location"
              item-title="name"
              item-value="id"
              :items="['all', ...locationStore.data.locations]"
              @update:modelValue="filter_change"
            />
            <AppSelect
              class="filter-field"
              density="compact"
              v-model="filter.status"
              placeholder="Status"
              :items="['all','Pending','Declined','Approved', 'Cancelled']"
              @update:modelValue="filter_change"
            />
            <AppSelect
              class="filter-field"
              density="compact"
              v-model="filter.timeFrame "
              placeholder="Time Frame "
              :items="['Monthly','Weekly','Fortnightly']"
            />
           <!--  <IconBtn>
                <VIcon icon="tabler-arrow-left" />
            </IconBtn>
            <IconBtn>
                <VIcon icon="tabler-arrow-right" />
            </IconBtn> -->

            <v-btn
              :rounded="true"
              icon="tabler-arrow-left"
              variant="outlined"
              color="secondary"
              
            />
           
            <v-btn
              rounded
              icon="tabler-arrow-right"
              variant="outlined"
              color="secondary"
              ></v-btn>
            <p>{{ filter.date_label }}</p>
          </div>
          <VSpacer />

        </VCardText>
        

        <VDivider />

        <!-- //EmployeeDetails-index -->
        <VDataTableServer
            :headers="tableHeaders"
            :items="items"
            height="300"
          >
          
            <template class="packingtest" v-for="header in tableHeaders" :key="header.key" #[`item.${header.key}`]="{ item }">
              <p v-if="header.key == 'full_name'"> {{ item.raw.employee.first_name }} {{ item.raw.employee.last_name }}</p>
              <span v-else :id="'headspan' + header.key +item.raw.id"  :class="{ 'highlight': header.date == item.raw.date_from }">
                aaaa
              </span>
              <!-- <span v-else :class="{ 'highlight': header.date == item.raw.date_from }">
             
              </span> -->

            </template>
        </VDataTableServer>
      </VCard>
    </section>
    
    <DialogLeaveDetails
      v-model:isDialogVisible="leaveDetailsVisible"
      v-model:leaveDetail="item_selected"
    />

  </div>
  
</template>


<script setup>
import { useEmployeeStore } from "@/store/employeeStore";
import { useRouter } from 'vue-router';

import DialogLeaveDetails from '@/pages/LeaveManagement/DialogLeaveDetails.vue';
import { useDivisionStore } from "@/store/divisionStore";
import { useEmploymentStore } from "@/store/employmentStore";
import { useLeaveBalanceStore } from "@/store/leaveBalanceStore";
import { useLeaveStore } from "@/store/leaveStore";
import { useLocationStore } from "@/store/locationStore";
import { debounce } from 'lodash';
import { VDataTableServer } from 'vuetify/labs/VDataTable';


const router = useRouter()
const leaveBalanceStore = useLeaveBalanceStore();
const employeeStore = useEmployeeStore();
const leaveStore = useLeaveStore();
const divisionStore = useDivisionStore();
const locationStore = useLocationStore();
const employmentStore = useEmploymentStore();
let items = ref([]);
let item_selected = ref({});
const leaveDetailsVisible = ref(false)
const startDate = new Date();
const endDate = new Date();
onMounted( async() => {
  items.value = await leaveStore.fetchLeaveByApprover();
  console.log(items.value);
  isHighlighted();
  //fetchLeaveByApprover
  //await divisionStore.setDivisions();
  //await locationStore.setLocations();
  //await employmentStore.setEmployments(); 
  
  //items.value = employeeStore.data.employees;
})

const headers1 = [
  {
    title: 'ID',
    key: 'id',
  },
  {
    title: 'Full Name',
    key: 'full_name',
  },
  {
    title: 'Job Title',
    key: 'employee.job_title',
  },
  {
    title: 'Date Filed',
    key: 'date_filed',
  },
  {
    title: 'Date From',
    key: 'date_from',
  },
  {
    title: 'Date To',
    key: 'date_to',
  },
  {
    title: 'Leave Type',
    key: 'leave_type.name',
  },
  {
    title: 'total_hours',
    key: 'total_hours',
  },
  {
    title: 'Action',
    key: 'action',
  }
]
const months = [
        {
          zoomTo: 100, // we want to display this format for all zoom levels until 100
          period: "month",
          format({ timeStart }) {
            return timeStart.format("MMM"); // full list of formats: https://day.js.org/docs/en/display/format
          }
        }
      ]
const days = [
        {
          zoomTo: 100, // we want to display this format for all zoom levels until 100
          period: "day",
          main: true, // we want grid to be divided by this period = day
          format({ timeStart }) {
            return timeStart.format("DD"); // full list of formats: https://day.js.org/docs/en/display/format
          }
        }
      ]
const config = {
        licenseKey: "",
        height: 300,

        list: {
          toggle: {
            display: false // toggle icon for show hide project names
          },
          rows: {
            1: {
              id: "1",
              style: { background: "red" },
              label: "Intranet",
              height: 30
            },
            2: {
              id: "2",
              label: " Dashboard",
              height: 30
            },
            3: {
              id: "3",
              label: "Purchase Verification",
              height: 30
            },
            4: {
              id: "4",
              label: "App Migration",
              height: 30
            },
            5: {
              id: "5",
              label: "Central Management",
              height: 30
            }
          },
          columns: {
            data: {
              id: {
                id: "id",
                data: "id",
                width: 50,

                header: {
                  content: "ID"
                }
              },
              label: {
                id: "label",
                data: "label",
                width: 200,
                header: {
                  content: "Projects"
                }
              }
            }
          }
        },
        chart: {
          calendarLevels: [
            [
              {
                zoomTo: 100, // we want to display this format for all zoom levels until 100
                period: "month",
                periodIncrement: 1,
                format({ timeStart }) {
                  return timeStart.format("MMMM"); // full list of formats: https://day.js.org/docs/en/display/format
                }
              }
            ]
          ],
          items: {
            1: {
              id: "1",
              rowId: "1",
              style: { background: "purple" },
              label: "Intranet",
              time: {
                start: new Date("01-02-2020").getTime(),
                end: new Date("06-10-2020").getTime()
              }
            },
            2: {
              id: "2",
              rowId: "2",
              style: { background: "blue" },
              label: "Item 2",
              time: {
                start: new Date("01-05-2020").getTime(),
                end: new Date("01-15-2020").getTime()
              }
            },
            3: {
              id: "3",
              rowId: "3",
              style: { background: "orange" },
              label: "Item 3",
              time: {
                start: new Date("01-10-2020").getTime(),
                end: new Date("01-20-2020").getTime()
              }
            },
            4: {
              id: "4",
              rowId: "4",
              style: { background: "green" },
              label: "Item 4",
              time: {
                start: new Date("01-13-2020").getTime(),
                end: new Date("01-18-2020").getTime()
              }
            },
            5: {
              id: "5",
              rowId: "5",
              style: { background: "red" },
              label: "Item 5",
              time: {
                start: new Date("01-15-2020").getTime(),
                end: new Date("01-20-2020").getTime()
              }
            }
          }
        }
      }

const filter = ref({
  search_name: '',
  division: 'all',
  location: 'all',
  status: 'all',
  timeFrame: 'Weekly',
  date_label: "May 2024"

})

const filter_change = debounce(async() => {
  const payload = {
    filter: {
      search_name: filter.value.search_name ? true : false,
      leave_type: true,
      division: true,
      location: true,
      status: true,
      filter_date: filter.value.filter_date ? true : false
    },
    data: {
      search_name: filter.value.search_name,
      leave_type_id: filter.value.leave_type,
      division_id: filter.value.division,
      location_id: filter.value.location,
      status: filter.value.status,
      filter_date: {
        from: filter.value.filter_date ? filter.value.filter_date[0] : null,
        to: filter.value.filter_date ? filter.value.filter_date[1] : null,
      }
    }
  }
  console.log("payload", payload);
  items.value = await leaveStore.multipleFilter(payload);
}, 800);
const isHighlighted = () => {
  //console.log('headspan' + header.key + item.id);
  let spann = document.getElementById('headspandate025');
  console.log("spann", spann);
  // let parentTd = spann.parentElement;
  // parentTd.className = "highlight";
};
const formatDate = (date) => {
  return `${date.toLocaleString('default', { month: 'long' })} ${date.getDate()}, ${date.getFullYear()}`;
}
const getStartDateOfWeek = () => {
  const today = new Date();
  const day = today.getDay();
  const diff = today.getDate() - day + (day === 0 ? -6 : 1); // Adjust to get the start of the week
  return new Date(today.setDate(diff));
}
const tableHeaders = computed(() => {
   const headers = [{ title: 'Full Name', key: 'full_name' }];
   console.log("filter.timeFrame", filter);
      if (filter.value.timeFrame === 'Weekly') {
        const startOfWeek = getStartDateOfWeek();
        for (let i = 0; i < 7; i++) {
          const date = new Date(startOfWeek);
          date.setDate(date.getDate() + i);
          headers.push({
            title: formatDate(date),
            key: `date${i}`,
            date: date.toISOString().split('T')[0]
          });
        }
      } else if (filter.value.timeFrame === 'Monthly') {
        const daysInMonth = new Date(startDate.getFullYear(), startDate.getMonth() + 1, 0).getDate();
        for (let i = 1; i <= daysInMonth; i++) {
          const date = new Date(startDate.getFullYear(), startDate.getMonth(), i);
          headers.push({
            title: formatDate(date),
            ket: `date${i}`,
            date: date.toISOString().split('T')[0]
          });
        }
      }
      console.log("headers", headers);
      return headers;
    
});
</script>
<style lang="scss" scoped>
.filter-field {
  min-width: 170px;
  max-width: 170px;
}
.highlight {
  background-color: yellow; /* Set the highlight color */
  display: block;
  width: 100% !important;
  height: 100% !important;

}
td {
  border: 1px;
}
.v-table > .v-table__wrapper > table > tbody > tr > td {
  padding: 0px !important;
}
</style>


