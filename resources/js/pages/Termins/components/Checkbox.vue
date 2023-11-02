<template>
  <template v-if="$attrs.checked">
    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
      <rect x="3" y="3" width="11" height="11" rx="1" fill="#AB326F" />
      <rect x="0.5" y="0.5" width="16" height="16" rx="1.5" stroke="#AB326F" />
    </svg>
  </template>
  <template v-else>
    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
      <rect x="0.5" y="0.5" width="16" height="16" rx="1.5" stroke="#AB326F" />
    </svg>
  </template>
</template>
<script setup></script>
