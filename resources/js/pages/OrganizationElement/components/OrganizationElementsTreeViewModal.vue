<template>
  <vue-final-modal
    v-model="show"
    name="organization-element-tree"
    classes="flex justify-center items-center"
    content-class="bg-white pt-5 pb-[66px] pr-5 pl-11 rounded-sm w-[776px] max-h-[605px] overflow-y-auto"
    :click-to-close="false"
    v-slot="{ close }"
  >
    <CrossIcon class="ml-auto cursor-pointer" @click="close" />
    <h1 class="text-[20px] mt-2 text-heading leading-6 font-semibold">
      {{ $t('Organization Chart') }}
    </h1>
    <div class="charts grid grid-cols-2 w-full justify-center gap-x-10 gap-y-12">
      <OrganizationElementChart v-for="(organization_element, index) in organization_elements" :key="index" :organization_element="organization_element" />
    </div>
  </vue-final-modal>
</template>

<script setup>
import { ref, computed } from '@vue/reactivity'

//Components
import CrossIcon from '@/components/icons/Cross.vue'
import OrganizationElementChart from './OrganizationElementChart.vue'
const props = defineProps({
  organization_elements: {
    default: () => [],
  },
})

const organization_elements = computed(() => {
  const childrens = [
    {
      Name: 'Test 1',
      id: 1,
    },
    {
      Name: 'Test 2',
      id: 2,
    },
    {
      Name: 'Test 3',
      id: 3,
    },
    {
      Name: 'Test 4',
      id: 4,
    },
    {
      Name: 'Test 5',
      id: 5,
    },
    {
      Name: 'Testssssss 6',
      id: 6,
    },
  ]

  return props.organization_elements.map((element) => {
    return {
      Name: element.name,
      Type: element.hierarchy_name,
      Responsible: 'Need Data',
      children: childrens.slice(0, Math.floor(Math.random() * 5) + 1),
    }
  })
})
const show = ref(false)
</script>
