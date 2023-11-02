<template>
    <button
      class="w-full hover:pointer font-inter text-white text-center rounded-[10px] px-[25px] py-[15px] font-medium flex justify-center items-center"
      @click="goToPage"
    >
    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-3.5">
        <path d="M9 17V9M9 9V1M9 9H17M9 9H1" stroke="white" stroke-width="2" stroke-linecap="round"/>
    </svg>
      <slot />
    </button>
  </template>

  <script>
    export default {
        props: {
            route: {
                type: [Object, String],
                required: true,
            },
        },
        methods: {
            goToPage(){
                this.$router.push(this.route)
            }
        }
    }
  </script>

  <style lang="scss" scoped>
      button {
          background: linear-gradient(262.9deg, #C52E62 -44.07%, #8B387F 155.41%);
          box-shadow: 1px 3px 15px rgba(0, 0, 0, 0.25);
          border-radius: 10px;
          font-size: 16px;
          line-height: 19px;
      }
  </style>
