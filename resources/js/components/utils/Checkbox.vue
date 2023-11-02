<template>
  <template v-if="$attrs.checked">
    <svg :width="`${$attrs.width ?? 14}`" :height="`${$attrs.height ?? 14}`" :viewBox="`0 0 ${$attrs.width ?? 14} ${$attrs.height ?? 14}`" fill="none" xmlns="http://www.w3.org/2000/svg">
      <rect x="0.5" y="0.5" :width="`${$attrs.width ? $attrs.width - 1 : 13}`" :height="`${$attrs.height ? $attrs.height - 1 : 13}`" rx="1.5" stroke="#AB326F" />
      <rect x="2" y="2" :width="`${$attrs.width ? $attrs.width - 4 : 10}`" :height="`${$attrs.height ? $attrs.height - 4 : 10}`" rx="1" fill="#AB326F" />
    </svg>
  </template>
  <template v-else>
    <svg :width="`${$attrs.width ?? 14}`" :height="`${$attrs.height ?? 14}`" :viewBox="`0 0 ${$attrs.width ?? 14} ${$attrs.height ?? 14}`" fill="none" xmlns="http://www.w3.org/2000/svg">
      <rect x="0.5" y="0.5" :width="`${$attrs.width ? $attrs.width - 1 : 13}`" :height="`${$attrs.height ? $attrs.height - 1 : 13}`" rx="1.5" stroke="#A4A4A4" />
    </svg>
  </template>
</template>
