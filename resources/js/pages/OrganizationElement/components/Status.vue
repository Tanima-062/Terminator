<template>
  <div class="status flex items-center gap-[8.3px]">
    <svg width="12" height="12" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
      <circle cx="5" cy="5" r="5" :fill="fillColor" />
    </svg>
    <span class="status-tooltip">{{ $t(statusText) }}</span>
  </div>
</template>

<script setup>
import { ref, computed } from '@vue/reactivity'
import { onMounted, inject } from '@vue/runtime-core'

const props = defineProps({
  organization_element: {
    type: Object,
    required: true,
  },
})

const emitter = inject('emitter')

const status = ref(props.organization_element.status)

const fillColor = computed(() => {
  if (status.value == 'Inactive') {
    return '#F93232'
  } else if (status.value == 'Active') {
    return '#439F6E'
  }

  return ''
})

const statusText = computed(() => {
  if (status.value == 'Inactive') {
    return 'inactive'
  } else if (status.value == 'Active') {
    return 'active'
  }

  return ''
})

onMounted(() => {
  emitter.on(`update-status-${props.organization_element.id}`, (value) => {
    status.value = value
    // console.log(status)
  })
})
</script>

<style lang="scss" scoped>
.status {
  position: relative;
  font-family: 'Inter';
  font-style: normal;
  font-weight: 400;
  font-size: 16px;
  line-height: 19px;

  color: #636363;

  // .status-tooltip {
  //     position: absolute;
  //     top: 0;
  //     left: 25px;
  //     display: none;
  //     padding: 5px 10px;
  //     border-radius: 4px;
  //     background-color: black;
  //     color:white;
  //     z-index: 999;
  //     font-size: 14px;
  //     line-height: 16px;
  //     // font-weight: 700;
  //     text-align: center;
  //     min-width: 70px;

  //     animation: .8s ease-in-out;

  //     &::before {

  //       content: "";
  //       width: 0;
  //       height: 0;
  //       border-style: solid;
  //       border-width: 5px 7.5px 5px 0;
  //       border-color: transparent black transparent transparent;
  //       display: inline-block;
  //       position: absolute;
  //       left: -7px;
  //       top: 50%;
  //       transform: translateY(-50%);
  //     }
  // }
}

svg:hover + .status-tooltip {
  display: block;
}
</style>
