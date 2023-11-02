<template>
  <vue-final-modal v-slot="{ close }" classes="flex justify-center items-center" content-class="bg-white pt-5 pr-5 pb-[64px] pl-[74px] rounded-sm w-[639px] " :keep-overlay="true" :click-to-close="false">
    <CrossIcon class="ml-auto cursor-pointer" @click="close" />
    <h1 class="text-[20px] mt-[18px] mb-[52px] text-heading leading-6 font-semibold">
      {{ $t(title) }}
    </h1>
    <SelectedInternalUsersView :users="users" :value="selected_users" :show_all="true" />

    <ButtonGradient class="w-max flex gap-[14px] items-center justify-center p-0 mt-[60px]" type="submit" @click="editInternalUser" v-if="!hide_edit_btn">
      <PencilIcon />
      <span>{{ $t('Edit') }}</span>
    </ButtonGradient>
  </vue-final-modal>
</template>

<script setup>
import CrossIcon from '@/components/icons/Cross.vue'
import { inject } from '@vue/runtime-core'
import SelectedInternalUsersView from './SelectedInternalUsersView.vue'
import ButtonGradient from '@/components/button/Gradient.vue'
import PencilIcon from '@/components/icons/PencilIcon.vue'
import InternalUserModificationModal from './InternalUserModificationModal.vue'
import { ref } from '@vue/reactivity'

const emit = defineEmits(['updateSubordinateUsers'])
const $vfm = inject('$vfm')

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  roles: {
    type: Array,
    default: () => [],
  },
  users: {
    type: Array,
    default: () => [],
  },
  value: {
    type: Array,
    required: false,
    default: () => [],
  },
  hide_edit_btn: {
    type: Boolean,
    default: false,
  },
})

const selected_users = ref([...props.value])

const editInternalUser = () => {
  const options = {
    component: InternalUserModificationModal,
    bind: {
      title: 'Edit internal user assignment',
      value: props.value,
      roles: props.roles,
      users: props.users,
      edit: true,
    },
    on: {
      save: (users) => {
        selected_users.value = users
        emit('updateSubordinateUsers', users)
      },
    },
  }
  $vfm.show(options)
}
</script>
