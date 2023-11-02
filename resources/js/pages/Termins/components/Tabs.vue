<template>
  <h1 class="text-formHeading text-title font-poppins mb-[60px]">{{ $route.name == 'termins-edit' ? $t('Edit contact record') : $t('appointment details') }}</h1>

  <div class="links flex h-[56px] gap-6 mb-[60px]">
    <button
      class="flex items-center justify-center text-heading font-[500] text-[22px] leading-[27px] border border-input rounded-t-[8px] rounded-b-[0px] bg-white w-[309px]"
      @click="$router.push({ name: 'termins-show', params: { id: $route.params.id } })"
      :class="{ 'bg-heading !text-white pointer-events-none': $route.name == 'termins-show' }"
      v-if="$route.name == 'termins-show' || $route.query.from == 'termins-show'"
    >
      {{ $t('Appointment') }}
    </button>
    <button
      class="flex items-center justify-center text-heading font-[500] text-[22px] leading-[27px] border border-input rounded-t-[8px] rounded-b-[0px] bg-white w-[309px]"
      @click="$router.push({ name: 'termins-edit', params: { id: $route.params.id } })"
      :class="{ 'bg-heading !text-white pointer-events-none': $route.name == 'termins-edit' }"
      v-if="$route.name == 'termins-edit' || $route.query.from == 'termins-edit'"
    >
      {{ $t('Appointment Edit') }}
    </button>
    <button
      class="flex items-center justify-center text-heading font-[500] text-[22px] leading-[27px] border border-input rounded-t-[8px] rounded-b-[0px] bg-white w-[309px]"
      :class="{ 'bg-heading !text-white pointer-events-none': $route.name == 'termins-show-history' }"
      @click="$router.push({ name: 'termins-show-history', params: { id: $route.params.id, }, query: {from: $route.name} })"
    >
      {{ $t('History') }}
    </button>
  </div>
</template>

<script>
export default {


}
</script>
