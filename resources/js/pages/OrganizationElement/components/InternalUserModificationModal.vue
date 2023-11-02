<template>
  <vue-final-modal v-slot="{ close }" classes="flex justify-center items-center" content-class="bg-white px-[77px] pt-5 pr-5 pb-[90px] pl-[54px] rounded-sm w-[546px] " :keep-overlay="true" :click-to-close="false">
    <CrossIcon class="ml-auto cursor-pointer" @click="close" />
    <h1 class="text-[20px] mt-4 text-heading leading-6 font-semibold">
      {{ $t(title) }}
    </h1>

    <div class="back flex gap-[11px] items-center cursor-pointer mt-9" @click="() => showCancelModal(close)" v-if="edit">
      <LeftArrowIcon />
      <span class="text-[16px] leading-[32px] text-[#636363] font-[500]">{{ $t('Back') }}</span>
    </div>

    <form class="w-[calc(100% - 20px)] h-max px-6 pt-[45px] pb-[37px] border border-input rounded-lg mr-5 mt-[60px]" :class="{ 'mt-6': edit }" @submit.prevent="() => submit(close)">
      <SingleSelect label="Role" placeholder="Select role" :options="roles" v-model="selected_role" class="mb-10" />

      <div class="wrapper">
        <MultiSelect :options="users" v-model="selected_users" label="User" class="mb-[56px]" :searchables="['first_name', 'last_name', 'label']" :asterisk="true">
          <template #selected-items-label="selected_items_labels">
            <div class="text-ellipsis text-[16px] overflow-hidden whitespace-nowrap text-[#636363]">
              <template v-for="(id, index) in selected_users" :key="index">
                <a
                  class="text-[#13A3E5] text-16"
                  @click.stop=""
                  :href="
                    $router.resolve({
                      name: 'organization-element-show',
                      params: { id },
                    }).href
                  "
                  target="_blank"
                >
                  {{ `${props.users.find(({ value }) => id == value)?.label}${index < selected_users.length - 1 ? ', ' : ''}` }}
                </a>
              </template>
            </div>
          </template>

          <template #placeholder-text>
            <div class="flex gap-2 items-center">
              <SearchIcon />
              <span>{{ $t('Search by user first or last name') }}</span>
            </div>
          </template>

          <template #options-top="{ search }">
            <div class="flex gap-[14px] pb-[6px]">
              <div class="select-all flex gap-[10px] items-center cursor-pointer" @click="() => (selected_users.length !== props.users.length ? selectAll() : null)">
                <Checkbox :checked="selected_users.length == props.users.length && props.users.length" />
                <p
                  class="text-[#636363] text-16"
                  :class="{
                    'text-[#ADB5BD]': selected_users.length == props.users.length || !props.users.length,
                  }"
                >
                  {{ $t('Select all') }}
                </p>
              </div>
              <div class="select-none flex gap-[10px] items-center cursor-pointer" @click="() => (selected_users.length ? deselectAll() : null)">
                <Checkbox :checked="selected_users.length == 0 && props.users.length" />
                <p
                  class="text-[#636363] text-16"
                  :class="{
                    'text-[#ADB5BD]': !selected_users.length || !props.users.length,
                  }"
                >
                  {{ $t('Select none') }}
                </p>
              </div>
            </div>
          </template>
        </MultiSelect>
      </div>

      <div class="btns flex justify-start gap-[18px]">
        <ButtonGradient class="min-w-[187px] w-max h-[48px] disabled:bg-[#BBBBBB] disabled:text-white disabled:bg-none px-0" type="submit" :disabled="selected_users.length < 1">
          {{ edit ? $t('Save') : $t('Assign user') }}
        </ButtonGradient>

        <ButtonWhite class="min-w-[187px] w-max" v-if="edit" type="button" @click="() => showCancelModal(close, false)">{{ $t('Cancel') }}</ButtonWhite>
      </div>
    </form>
  </vue-final-modal>
</template>

<script setup>
import { ref, computed } from '@vue/reactivity'
import ButtonGradient from '@/components/button/Gradient.vue'
import SingleSelect from '@/components/form/SingleSelect.vue'
import MultiSelect from '@/components/form/MultiSelect.vue'
import Checkbox from '@/components/utils/Checkbox.vue'
import CrossIcon from '@/components/icons/Cross.vue'
import { inject } from '@vue/runtime-core'
import ButtonWhite from '@/components/button/White.vue'
import LeftArrowIcon from '@/components/icons/LeftArrow.vue'
import SearchIcon from '@/components/icons/Search.vue'
import { useIsEqual } from '@/composables/utils.js'

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
  edit: {
    type: Boolean,
    default: false,
  },
})

const selected_users = ref([...props.value])
const selected_role = ref('')
const emit = defineEmits(['save'])
const $vfm = inject('$vfm')

const users = computed(() => {
  return props.users.filter((user) => (selected_role.value ? selected_role.value == user.role : true))
})

const selectAll = () => {
  selected_users.value = props.users.map(({ value }) => value)
}

const deselectAll = () => {
  selected_users.value = []
}

const submit = async (close) => {
  $vfm.show('confirmation', {
    title: 'Assign user?',
    description: 'Are you sure you want to add the selected user(s) to this organizational element as a direct subordinate user?',
    yesClick: () => {
      emit('save', selected_users.value)
      close()
    },
  })
}

const showCancelModal = (close, equal_check = true) => {
  if (equal_check && useIsEqual(props.value, selected_users.value)) {
    return close()
  }
  $vfm.show('confirmation', {
    title: 'Discard changes?',
    description: 'If you go back or cancel without saving, all changes will be discarded. Are you sure you really want to discard the changes?',
    yesClick: close,
  })
}
</script>
