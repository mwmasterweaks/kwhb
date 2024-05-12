<script setup>
import { app } from "@/main"
import { ProfilePlaceHolder } from '@/plugins/profilePlaceHolder'
import { useEmployeeStore } from "@/store/employeeStore"
import { toast } from 'vue3-toastify'

const employeeStore = useEmployeeStore()
const profileHeaderData = ref()
const upload_image = ref(null)
const profile_upload_image = ref(null)
const loading = ref(false)
const attachment_path = ref(app.config.globalProperties.$attachment_path)

profileHeaderData.value = employeeStore.data.employee_selected
console.log('profile', profileHeaderData.value)

const initials = computed(() => {
  return (
    profileHeaderData.value.first_name.charAt(0).toUpperCase() +
    profileHeaderData.value.last_name.charAt(0).toUpperCase()
  )
})

const form = ref({
  image_url: profileHeaderData.value.cover_image ? attachment_path.value + "cover_image/" +  profileHeaderData.value.cover_image.file_name : null,
  image_data: null,
  profile_image_url: profileHeaderData.value.profile_image ? attachment_path.value + "profile_image/" +  profileHeaderData.value.profile_image.file_name
    : ProfilePlaceHolder(initials.value),
  profile_image_data: null,
  job_title: profileHeaderData.value.job_title ?? '',
  phone: profileHeaderData.value.work_phone ?? '',
  personal_email: profileHeaderData.value.personal_email ?? '',
  work_email: profileHeaderData.value.work_email ?? '',
})

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
</script>

<template>
  <div>
    <VRow>
      <VCol cols="12">
        <VCard v-if="profileHeaderData">
          <div
            :class="form.image_url ? 'cover-photo' : 'cover-photo-wrapper'"
            style="block-size: 250px;"
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
            <span class="top-right px-5 py-1">Member # 0001</span>
            <VIcon
              class="bottom-right-icon mr-3 mb-3 pa-1"
              icon="tabler-pencil"
              color="#000"
              size="25"
              @click="triggerSelectImage('cover')"
            />
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
            <div class="d-flex h-0">
              <!--
                <VAvatar
                rounded
                size="120"
                image="http://localhost:5173/resources/images/avatars/avatar-1.png"
                class="user-profile-avatar mx-auto"
                /> 
              -->
              <VAvatar
                rounded
                size="120"
                :image="form.profile_image_url ? form.profile_image_url : $attachment_path + 'profile_image/' + 'default.png'"
                class="user-profile-avatar mx-auto"
              />
            </div>
            <VIcon
              size="24"
              icon="tabler-pencil"
              color="primary"
              class="position-absolute bottom-0 end-0 mb-n4 mr-n4"
              @click="triggerSelectImage('profile')"
            />
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
                      {{ profileHeaderData?.division.name }}
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
                    <VIcon
                      size="20"
                      icon="tabler-calendar"
                      class="me-1"
                    />
                    <span class="text-body-1">
                      {{ $formatDate(profileHeaderData.date_hired) }}
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
                <VBtn
                  prepend-icon="tabler-check"
                  color="success"
                >
                  Active
                </VBtn>
              </div>
            </div>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </div>
</template>

<style lang="scss">
.user-profile-avatar {
  border: 5px solid rgb(var(--v-theme-surface));
  background-color: rgb(var(--v-theme-surface)) !important;
  inset-block-start: -3rem;

  .v-img__img {
    border-radius: 0.125rem;
  }
}
</style>
