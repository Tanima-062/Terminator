<template>
  <template v-if="$attrs.active">
    <svg
      width="15"
      height="16"
      viewBox="0 0 15 16"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
    >
      <rect x="0.5" y="0.5" width="14" height="14.0207" rx="7" stroke="#A4A4A4" />
      <rect x="3" y="3" width="9" height="9" rx="4.5" fill="#AB326F" />
    </svg>
  </template>
  <template v-else>
    <svg
      width="15"
      height="16"
      viewBox="0 0 15 16"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
    >
      <rect x="0.5" y="0.5" width="14" height="14.0207" rx="7" stroke="#A4A4A4" />
    </svg>
  </template>
</template>
