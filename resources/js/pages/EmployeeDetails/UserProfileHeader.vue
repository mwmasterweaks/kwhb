<script setup>
import { ProfilePlaceHolder } from '@/plugins/profilePlaceHolder'
import { useEmployeeStore } from "@/store/employeeStore"
import '@vuepic/vue-datepicker/dist/main.css'
import { getCurrentInstance, ref } from 'vue'
import { useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'

const app = getCurrentInstance().appContext.app
const router = useRouter()
const employeeStore = useEmployeeStore()

// const vDatePicker = VDatepicker()
const profileHeaderData = ref()
const upload_image = ref(null)
const profile_upload_image = ref(null)
const loading = ref(false)
const attachment_path = app.config.globalProperties.$attachment_path

// const selectedDate = ref('2024-05-19')
const selectedDate = ref()
const statusSelected = ref()
const datePickerRef = ref(null)
const isMenuOpen = ref(false)


profileHeaderData.value = employeeStore.data.employee_selected
if(!profileHeaderData.value.first_name)
{
  console.log("No employee selected")
  router.push("employee")
}
console.log('profile', profileHeaderData.value)

const initials = computed(() => {
  return (
    profileHeaderData.value.first_name.charAt(0).toUpperCase() +
    profileHeaderData.value.last_name.charAt(0).toUpperCase()
  )
})

const form = ref({
  image_url: profileHeaderData.value.cover_image ? profileHeaderData.value.cover_image.file_name : null,
  image_data: null,
  //profile_image_url: profileHeaderData.value.profile_image ? attachment_path + "profile_image/" +  profileHeaderData.value.profile_image.file_name
  profile_image_url: profileHeaderData.value.profile_image ? profileHeaderData.value.profile_image.file_name
    : ProfilePlaceHolder(initials.value),
  profile_image_data: null,
  job_title: profileHeaderData.value.job_title ?? '',
  phone: profileHeaderData.value.work_phone ?? '',
  personal_email: profileHeaderData.value.personal_email ?? '',
  work_email: profileHeaderData.value.work_email ?? '',
})

console.log(form.value)

const triggerSelectImage = type => {
  if (type == 'cover') {
    upload_image.value.click()
  } else {
    profile_upload_image.value.click()
  }
}

const onSelectFile = type => {
  let input = null
  if (type == 'cover') {
    input = upload_image
  } else {
    input = profile_upload_image
  }
  const files = input.value.files
  if (files && files[0]) {
    const size = (files[0].size / 1024 / 1024).toFixed(2)
    if (size > 5) {
      toast(
        'error',
        'File size too large. Please select file not exceeding to 5mb',
      )

      return
    }
    let reader = new FileReader()
    reader.onload = e => {
      if (type == 'cover') {
        form.value.image_data = files[0]
        form.value.image_url = e.target.result
      } else {
        form.value.profile_image_data = files[0]
        form.value.profile_image_url = e.target.result
      }
      console.log('on select file', form.value)
    }
    reader.readAsDataURL(files[0])
  }
}

const cancelBtn = type => {
  console.log('cancelBtn', type)
  if (type == 'cover') {
    form.value.image_data = ''
    upload_image.value = ''
    form.value.image_url = profileHeaderData.value.cover_photo
      ? profileHeaderData.value.cover_image
      : null

    console.log('image url', form.value.image_url)
  } else {
    form.value.profile_image_data = ''
    profile_upload_image.value = ''
    form.value.profile_image_url = profileHeaderData.value.picture
      ? profileHeaderData.value.profile_image
      : ProfilePlaceHolder(initials.value)
  }
}

const saveBtn = async type => {
  console.log('saveBtn', type)
  var attachment_id = null
  var image_url = null
  if (type == 'cover') {
    attachment_id = profileHeaderData.value.cover_image != null ? profileHeaderData.value.cover_image.id : null
    image_url = form.value.image_url
  } else if (type == 'profile') {
    attachment_id = profileHeaderData.value.profile_image != null ? profileHeaderData.value.profile_image.id : null
    image_url = form.value.profile_image_url
  }

  // pag save dapat set ang form image url
  const formData = {
    image_url: image_url,
    image: type == 'cover' ? 'cover_image' : 'profile_image',
    attachment_id: attachment_id,
    emp_id: profileHeaderData.value.id,
  }

  try {
    // loading.value = true

    const response = await employeeStore.updateAttachment(formData)

    toast('success', response.message)
    console.log(response)

    // loading.value = false

    // form.value.image_url = pro
    form.value.image_data = ''
    upload_image.value = ''
    form.value.profile_image_data = ''
    profile_upload_image.value = ''
  } catch (error) {
    loading.value = false
  }
}

const updateRow = async (row, data)=>{
  var update = await employeeStore.updateRow({
    id: profileHeaderData.value.id,
    data,
    dateSelected: selectedDate.value,
    row,
  })
  toast("Updated!")
  console.log(update)
}

const formatEmployeeNumber = num => {
  return String(num).padStart(5, '0')
}

const formatDateToMonthYear = (dateString) => {
  // Create a new Date object from the input string
  const date = new Date(dateString);
  
  // Define an array with the month names
  const monthNames = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
  ];

  // Get the month name and the year
  const monthName = monthNames[date.getMonth()];
  const year = date.getFullYear();

  // Return the formatted string
  return `${monthName} ${year}`;
}

const onMenuOpen = (value) => {
  console.log(value);
  if (value) {
    setTimeout(async () => {
      await nextTick()
      console.log(datePickerRef.value)

      const datePickerElement = datePickerRef.value.$el.querySelector('.dp__flex_display')

      console.log(datePickerElement)
      if (datePickerElement) {
        datePickerElement.classList.remove('dp__flex_display')
      }
    }, 100) // Adjust the delay as needed
  }
}

const formatDate = dateStr => {
  let date

  if (dateStr.includes('/')) {
    const [month, day, year] = dateStr.split('/').map(Number)

    date = new Date(year, month - 1, day)
  }
  else if (dateStr.includes('-')) {
    const [year, month, day] = dateStr.split('-').map(Number)

    date = new Date(year, month - 1, day)
  } 
  else {
    throw new Error('Invalid date format. Please use mm/dd/yyyy or yyyy-mm-dd.')
  }

  // return `${date.toLocaleString('default', { month: 'long' })} ${date.getDate()}, ${date.getFullYear()}`
  return `${date.toLocaleString('default', { month: 'long' })} ${date.getFullYear()}`
}
</script>

<template>
  <div>
    <VRow>
      <VCol cols="12">
        <VCard v-if="profileHeaderData">
          <div
            :class="form.image_url ? 'cover-photo' : 'cover-photo-wrapper'"
            style=" position: relative;block-size: 250px;"
            :style="
              form.image_url
                ? { backgroundImage: 'url(' + form.image_url + ')'}
                : {
                  background: 'rgb(250, 160, 255)',
                  background:
                    'linear-gradient(0deg, rgba(250, 160, 255, 100%) 0%, rgba(248, 115, 255, 100%) 40%, rgba(207, 30, 255, 100%) 100%)',
                }
            "
          >
            <span
              class="top-right px-5 py-1"
              style="position: absolute; top: 0; right: 0; border-radius: 0 0 0 5px; background-color: #ffffff80; color: #000;"
            >Employee # {{ formatEmployeeNumber(profileHeaderData?.id) }}</span>
            <span
              class="bottom-right-icon mr-3 mb-3 pa-1"
              style="position: absolute; right: 0; bottom: 0; border-radius: 5px; background-color: #ffffff60; color: #000;"
            >
              <VIcon
                icon="tabler-pencil"
                color="#000"
                size="20"
                @click="triggerSelectImage('cover')"
              /></span>
            
            <input
              ref="upload_image"
              type="file"
              name="upload_image"
              class="upload_image"
              accept="image/png, image/jpeg"
              style="display: none;"
              @input="onSelectFile('cover')"
            >
          </div>

          <VCardText class="d-flex align-bottom flex-sm-row flex-column justify-center gap-x-5">
            <div class="d-flex h-0 position-relative">
              <VAvatar
                rounded
                size="120"
                :image="profileHeaderData.profile_image ? form.profile_image_url : $attachment_path + 'profile_image/' + 'default.png'"
                class="user-profile-avatar mx-auto"
              />
              <VIcon
                size="24"
                icon="tabler-pencil"
                color="black"
                class="position-absolute mt-n11"
                style="margin-left: 90px;"
                @click="triggerSelectImage('profile')"
              />
            </div>
            <input
              ref="profile_upload_image"
              type="file"
              name="profile_upload_image"
              class="upload_image"
              accept="image/png, image/jpeg"
              style="display: none;"
              @input="onSelectFile('profile')"
            >

            <div class="user-profile-info w-100 mt-16 pt-6 pt-sm-0 mt-sm-0">
              <h5 class="text-h5 text-center text-sm-start font-weight-medium mb-3">
                {{ profileHeaderData?.first_name }} {{ profileHeaderData?.last_name }}
              </h5>

              <div class="d-flex align-center justify-center justify-sm-space-between flex-wrap gap-4">
                <div class="d-flex flex-wrap justify-center justify-sm-start flex-grow-1 gap-4">
                  <span class="d-flex">
                    <VIcon
                      size="20"
                      icon="tabler-color-swatch"
                      class="me-1"
                    />
                    <span class="text-body-1">
                      {{ profileHeaderData?.job_title }}
                    </span>
                  </span>
                  <span class="d-flex">
                    <VIcon
                      size="20"
                      icon="tabler-map-pin"
                      class="me-1"
                    />
                    <span class="text-body-1">
                      {{ profileHeaderData?.location.name }}
                    </span>
                  </span>
                  <span class="d-flex">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="20"
                      height="20"
                    ><path
                      d="m9.988 1.363.518-.012c.98-.006 1.626.036 2.424.669.543.543.618 1.086.808 1.818l.439.015 1.96.074.69.024.66.026.609.022c.49.042.49.042.895.244l.052 4.836.024 2.246.02 2.17.011.825c.047 2.427.047 2.427-.58 3.207-1.164.982-2.111 1.083-3.601 1.089l-.734.004H13.4l-.813.002-1.701.002c-.724 0-1.447.002-2.17.006l-1.68.002-1.187.005c-2.894-.007-2.894-.007-4.003-.947-.627-.756-.66-1.326-.683-2.277l-.018-.716-.016-.775-.016-.795-.033-1.665-.048-2.134L.99 7.293l-.02-.775-.012-.716-.013-.632c.067-.522.067-.522.342-.89.47-.337.832-.325 1.405-.34l.63-.023.655-.014.664-.02 1.621-.043.119-.333.159-.438.156-.433c.171-.413.171-.413.576-1.02.866-.315 1.804-.246 2.715-.252zM7.879 3.232l.202.808 1.793.013.515.005.494.001.455.004c.398 0 .398 0 .783-.225v-.606c-1.446-.89-2.797-.89-4.243 0zM2.627 5.858c.041.45.041.45.201 1.01l.097.414.358 1.43.117.497c.254.97.254.97.908 1.707.589.213 1.039.277 1.664.272l.569.005c.627-.095.898-.23 1.337-.687l.202-1.01h3.637l.404 1.212c.695.464.975.459 1.793.455l.63.003c.629-.056 1.063-.16 1.618-.458a9.091 9.091 0 0 0 .605-1.615l.171-.61c.255-.962.474-1.825.435-2.825-.579-.289-1.076-.229-1.723-.231l-.41-.002-1.345-.002-.933-.002-1.957-.002c-.841 0-1.67-.002-2.51-.006l-1.927-.005-.925-.002H4.349l-.744-.001c-.602-.01-.602-.01-.98.454zm6.641 4.861c-.446.386-.446.386-.48 1.086.114.81.279 1.035.91 1.528.752-.226.752-.226 1.212-.809.084-.698.084-.698 0-1.414-.353-.408-.353-.408-.808-.606-.425-.03-.425-.03-.834.215zm-6.843.796c-.464.464-.268 1.537-.277 2.16l-.023.55-.007.538-.013.49c.135.579.314 1.028.718 1.472.999.596 2.351.407 3.485.392h.915l1.911-.012 2.45-.007 1.885-.005.904-.001 1.261-.012.725-.006c.734-.126.974-.328 1.42-.913.11-.506.11-.506.08-1.051l-.008-.6-.021-.622-.01-.631-.04-1.54c-.818-.075-.818-.075-1.414.404-.39.055-.39.055-.826.062l-.48.015-.996.02c-.96.029-.96.029-1.839.383-.377.407-.51.824-.707 1.338-.65.433-.862.464-1.616.468l-.53.01c-.577-.089-.73-.227-1.085-.68l-.417-.631c-.33-.591-.33-.591-.796-.783l-2.162-.086c-.891-.037-1.4-.095-2.08-.722h-.404z"
                      fill="#858998"
                    /><path
                      d="M12.525 3.233h.606l.202 1.01 5.455.201v11.718l-.606.201-.202-4.848-1.212-.404v.606h-.606v.404l-4.647.202V11.11l.343.003 1.538.01.539.005.52.001.477.004c.46-.007.46-.007 1.027-.225.693-1.687.997-3.479 1.414-5.253H3.03c-.224.897-.144 1.13.164 1.97.262.732.492 1.435.644 2.197.125.681.125.681.549.923.551.194 1.001.216 1.585.213l.581.004c.566-.019.566-.019 1.124-.458l.201-1.01h3.637v1.213l-.413-.052-.546-.062-.54-.065a3.475 3.475 0 0 0-1.33.178l.089.96.05.54c.06.575.06.575.266 1.33h1.818v-.606h.606v.808h-.404v.405H8.687c-.404-1.01-.404-1.01-.404-1.616-1.057-.257-2.12-.264-3.202-.29-.896-.037-1.371-.144-2.051-.721h-.808l-.202 4.242-.404.202c-.037-3.842.069-7.676.202-11.515l.389-.01 1.757-.053.612-.014c1.126-.041 1.94-.122 2.898-.731l.202.404 4.848.202v-1.01h.001z"
                      fill="#818595"
                    /><path
                      d="M12.525 1.818c.672.381.8.58 1.037 1.338l.175.682.438.015 1.96.074.69.024.66.026.61.022c.49.042.49.042.894.244l.053 4.836.024 2.246.02 2.17.01.825c.047 2.427.047 2.427-.58 3.207-1.163.982-2.111 1.083-3.601 1.089l-.733.004h-.784l-.814.002-1.7.002c-.725 0-1.447.002-2.171.006l-1.68.002-1.186.005c-2.975-.007-2.975-.007-3.826-.859l.517.099c.714.106 1.391.135 2.112.145l.413.005 1.337.017.933.013 2.445.03 2.498.033 4.896.062v-.405h.605v-.404h.405v-.808l-1.213.404.3-.338c.366-.562.377-.857.365-1.521l-.006-.6-.015-.622-.008-.631-.03-1.54-.38.189c-.603.301-.97.444-1.646.466l-.485.02-1.012.035-.485.021-.445.015c-.46.043-.46.043-1.002.465l-.19.493c-.253.614-.46.781-1.022 1.123-.905.161-1.745.19-2.512-.34l-.921-1.476c-.667-.334-1.412-.28-2.147-.316l-.49-.027-1.201-.062-.202-.404 1.856.076.529.02c1.768.08 1.768.08 2.463.51.254.533.3 1.03.405 1.617h2.424v-.404h.404v-.809h-.606v.606H9.092c-.391-.782-.451-1.04-.43-1.868l.011-.546.014-.414c.811-.465 1.306-.385 2.223-.201l.606.201V9.495H8.08v-.202h3.637l.605 1.415c.544.271.973.229 1.579.227l.635.002a8.617 8.617 0 0 0 1.625-.23l-.202.405c-.7.233-1.257.229-1.995.227l-.743.002a8.444 8.444 0 0 1-1.706-.23l.202 1.01h4.444v-.403h.605v-.606c.48-.025.48-.025 1.01 0 .518.517.465.74.488 1.46l.023.631.02.662.023.666.051 1.632h.405V4.444h-5.454c-.374-.374-.283-.91-.339-1.411a3.067 3.067 0 0 0-.468-1.215h-.001zM9.268 10.72c-.447.386-.447.386-.48 1.086.113.809.278 1.035.909 1.527.752-.225.752-.225 1.212-.808.084-.698.084-.698 0-1.414-.353-.408-.353-.408-.808-.606-.424-.03-.424-.03-.834.215z"
                      fill="#9699A6"
                    /><path
                      d="m3.993 5.018.434-.003 1.424-.006.987-.003 2.071-.004 2.657-.012 2.039-.005.98-.005 1.37-.002.788-.002c.632.074.632.074 1.024.344.214.337.214.337.214.943h-.404v1.615h-.405l-.081.414-.108.546-.107.54-.31 1.329h-.404l.162-.644.216-.859.107-.42.538-2.274.108-.488.08-.366-5.579-.042-2.59-.019-2.5-.016-.954-.009-1.335-.007-.768-.006c-.639.037-.639.037-1.007.507l-.217.398v-1.21c.384-.383 1.06-.231 1.57-.234z"
                      fill="#9295A3"
                    /><path
                      d="M3.233 16.97h13.535c-.606.606-.606.606-1.163.68l-.68-.005h-.772l-.835-.01-.853-.002-2.245-.015-2.29-.011-4.495-.03-.202-.606v-.001z"
                      fill="#9598A6"
                    /><path
                      d="m1.213 4.243.404.201V15.96c.326-.326.246-.698.266-1.15l.025-.578.025-.608.026-.61.062-1.499 1.01-.201.404.605-1.01-.201-.05 1.944-.022.553c-.017 1.193.105 1.836.88 2.755-.809 0-.809 0-1.162-.303l-.253-.303.202 1.414c-.585-.534-.79-.926-.842-1.735l-.011-.634-.014-.72-.013-.776-.014-.796-.028-1.668-.037-2.139-.035-2.038-.015-.778L1 5.775l-.01-.633c.018-.497.018-.497.22-.901l.002.002z"
                      fill="#9498A5"
                    /><path
                      d="M3.434 8.89h.202l.237 1.223c.137.417.137.417.516.644.549.183 1.004.206 1.583.203l.581.003c.566-.018.566-.018 1.124-.457.147-.518.147-.518.201-1.01h.202l-.088.897-.05.504c-.065.417-.065.417-.266.619l-.815.02-.495-.002-.521-.005-.523-.002-1.282-.01v-.606h-.404c-.325-.65-.214-1.3-.202-2.02v-.002z"
                      fill="#9295A3"
                    /><path
                      d="M10.91 10.505c.236.473.228.763.227 1.288l.001.471c-.026.463-.026.463-.229 1.271H9.091c-.391-.782-.451-1.04-.43-1.868l.011-.546.015-.413c.805-.463 1.313-.408 2.222-.202v-.001zm-1.643.215c-.446.386-.446.386-.48 1.086.114.809.279 1.035.91 1.527.752-.225.752-.225 1.212-.808.084-.698.084-.698 0-1.414-.353-.408-.353-.408-.808-.606-.424-.03-.424-.03-.834.215z"
                      fill="#969AA7"
                    /><path
                      d="m10.013 1.377.528-.008h.507l.463-.003c.408.049.408.049 1.014.453l-.438.01-1.96.054-.691.014-.66.021-.608.016-.491.088-.404.606h-.405l-.201.806c-.05-.598-.043-.933.252-1.465.796-.796 2.041-.592 3.094-.593z"
                      fill="#9B9EAA"
                    /><path
                      d="M16.97 5.454h.606v2.424h-.404l-.082.414-.108.546-.107.54-.31 1.329h-.404l.162-.644 1.05-4.407-.405-.202h.002z"
                      fill="#979BA7"
                    /><path
                      d="m8.08 2.424 1.567-.05.446-.022c.75-.013 1.134.002 1.729.479l.3.4-.493-.201c-1.069-.3-2.057-.335-3.132-.038-.477.235-.477.235-.82.846v-1.01h.404v-.404z"
                      fill="#9598A5"
                    /><path
                      d="m10.025 10.088.5.006.383.008v.404l-.656-.013c-.586.01-1.018.04-1.566.215v-.404c.486-.243.798-.222 1.338-.215h.001z"
                      fill="#888C9B"
                    /><path
                      d="m7.273 1.616.808.202-.808.808h-.405l-.201.808c-.054-.636-.05-.933.303-1.477l.303-.341z"
                      fill="#999DA9"
                    /></svg>
                    <span class="text-body-1 ml-2">
                      {{ profileHeaderData?.division.name }}
                    </span>
                  </span>
                  <span class="d-flex">
                    <VIcon
                      size="20"
                      icon="tabler-calendar"
                      class="me-1"
                    />
                    <span class="text-body-1" v-if="profileHeaderData.date_hired != null">
                      Joined {{ formatDateToMonthYear(profileHeaderData.date_hired) }}
                    </span>
                  </span>
                </div>

                <div
                  v-if="form.image_data || form.profile_image_data"
                  class="top-button"
                >
                  <VBtn @click="form.image_data ? saveBtn('cover') : saveBtn('profile')">
                    Save Changes
                  </VBtn>
                  <VBtn
                    class="ml-2"
                    color="secondary"
                    @click="form.image_data ? cancelBtn('cover') : cancelBtn('profile')"
                  >
                    Cancel
                  </VBtn>
                </div>
                <VMenu
                  v-model="isMenuOpen"
                  location="bottom"
                  offset-y
                  transition="scale-transition"
                  :close-on-content-click="false"
                  @update:modelValue="onMenuOpen"
                >
                  <template #activator="{ props }">
                    <VBtn
                      append-icon="tabler-chevron-down"
                      color="success"
                      class="date-picker-btn"
                      v-bind="props"
                    >
                      Active
                    </VBtn>
                  </template>
                 <VCard>
                    <!-- <VRow class="pa-1  ma-2">
                      <VCol cols="12">
                        <VueDatePicker
                          ref="datePickerRef"
                          v-model="selectedDate"
                          inline
                          auto-apply
                        />
                      </VCol>
                    </VRow> -->
                    <div class="hide-input">
                      <AppDateTimePicker
                        v-model="selectedDate"
                        :config="{ inline: true }"
                      />
                    </div>
                    

                    <VDivider />
                    <AppSelect
                      v-model="statusSelected"
                      class="pa-3 ma-2"
                      placeholder="Active"
                      :items="employeeStore.data.statuses"
                    />
                    <VRow class="pa-3  ma-2">
                      <VCol cols="9">
                        Active Until: {{ $formatDate(selectedDate) }}
                      </VCol>
                      <VCol cols="2">
                        <VBtn
                          variant="tonal"
                          color="success"
                          @click=" updateRow('status',statusSelected)"
                        >
                          Save
                        </VBtn>
                      </VCol>
                    </VRow>
                  </VCard>
                </VMenu>
              </div>
            </div>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </div>
</template>

<style lang="scss" >
.user-profile-avatar {
  border: 5px solid rgb(var(--v-theme-surface));
  background-color: rgb(var(--v-theme-surface)) !important;
  inset-block-start: -3rem;

  .v-img__img {
    border-radius: 0.125rem;
  }

  .v-picker-title .v-date-picker-header {
    display: none !important;
  }
}
.hide-input .v-input {
  display: none !important;
}
.hide-input .flatpickr-calendar {
  width: auto;
}
.hide-input .flatpickr-calendar .flatpickr-innerContainer {
  width: auto;
}
.hide-input .flatpickr-calendar .flatpickr-innerContainer .flatpickr-rContainer{
  width: auto;
}
.hide-input .flatpickr-calendar .flatpickr-innerContainer .flatpickr-rContainer .flatpickr-days{
  width: auto;
}
.hide-input .flatpickr-calendar .flatpickr-innerContainer .flatpickr-rContainer .flatpickr-days .dayContainer{
  width: auto;
}
</style>
