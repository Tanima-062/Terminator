<template>
  <h1 class="text-formHeading text-title font-poppins mb-[60px]">{{ $route.name == 'contact-data-records-edit' ? $t('Edit contact record') : $t('Contact record details') }}</h1>

  <div class="links flex h-[56px] gap-6 mb-[60px]">
    <button
      class="flex items-center justify-center text-heading font-[500] text-[22px] leading-[27px] border border-input rounded-t-[8px] rounded-b-[0px] bg-white w-[309px]"
      @click="$router.push({ name: `${$route.query.from_edit ? 'contact-data-records-edit' : 'contact-data-records-show'}`, params: { id: $route.params.id } })"
      :class="{ 'bg-heading !text-white': $route.name !== 'contact-data-records-show-history' }"
    >
      {{  $t('Contact record') }}
    </button>
    <button
      class="flex items-center justify-center text-heading font-[500] text-[22px] leading-[27px] border border-input rounded-t-[8px] rounded-b-[0px] bg-white w-[309px]"
      :class="{ 'bg-heading !text-white': $route.name == 'contact-data-records-show-history' }"
      @click="$router.push({ name: 'contact-data-records-show-history', params: { id: $route.params.id }, query: { from_edit: $route.name == 'contact-data-records-edit' ? 1 : 0 } })"
    >
      {{ $t('Contact record history') }}
    </button>
  </div>
</template>

<script setup>
</script>
