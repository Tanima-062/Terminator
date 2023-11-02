<template>
  <div class="col-span-2 row-span-1 flex justify-between items-center mx-6 pt-4">
    <div class="flex items-center gap-5">
      <HamburgerIcon class="cursor-pointer" @click="$emit('toggleNavbar')"/>
      <LogoIcon />
    </div>
    <div class="flex items-center">
      <div class="avatar w-[50px] h-[50px] bg-[#EADDE8] rounded-full flex items-center justify-center mr-2 overflow-hidden">
        <img
            :src="user.photo_url"
            alt="admin"
            class=" object-contain"
            v-if="user.photo_url"
        />
        <svg width="23" height="27" viewBox="0 0 23 27" fill="none" xmlns="http://www.w3.org/2000/svg" v-else>
                <path d="M23 22.3418C23 23.6191 22.6257 24.7148 21.877 25.6289C21.1283 26.543 20.2268 27 19.1727 27H3.82734C2.77318 27 1.87174 26.543 1.12305 25.6289C0.374349 24.7148 0 23.6191 0 22.3418C0 21.3457 0.0509115 20.4053 0.152734 19.5205C0.254557 18.6357 0.443229 17.7451 0.71875 16.8486C0.994271 15.9521 1.34466 15.1846 1.76992 14.5459C2.19518 13.9072 2.7582 13.3857 3.45898 12.9814C4.15977 12.5771 4.95317 13.0248 5.5 14C6.62148 16 9.31979 16.8486 11.5 16.8486C13.6802 16.8486 16.3785 16.2691 17.5 14C17.9954 12.9977 18.8402 12.5771 19.541 12.9814C20.2418 13.3857 20.8048 13.9072 21.2301 14.5459C21.6553 15.1846 22.0057 15.9521 22.2812 16.8486C22.5568 17.7451 22.7454 18.6357 22.8473 19.5205C22.9491 20.4053 23 21.3457 23 22.3418ZM18.4 6.75C18.4 8.61328 17.7262 10.2041 16.3785 11.5225C15.0309 12.8408 13.4047 13.5 11.5 13.5C9.59531 13.5 7.96914 12.8408 6.62148 11.5225C5.27383 10.2041 4.6 8.61328 4.6 6.75C4.6 4.88672 5.27383 3.2959 6.62148 1.97754C7.96914 0.65918 9.59531 0 11.5 0C13.4047 0 15.0309 0.65918 16.3785 1.97754C17.7262 3.2959 18.4 4.88672 18.4 6.75Z" fill="#8B387F" fill-opacity="0.81"/>
        </svg>
      </div>
      <div class="user_information">
        <h4 class="">{{ user.name }}</h4>
        <p class="">{{ (user.type=='broker_user' && user.role == 'Admin') ? $t('Broker Admin') : $t(user.role) }}</p>
      </div>
      <div class="nav_dropdown_menu hover:cursor-pointer ml-[18px] relative" @click="dropdownOpen = !dropdownOpen">
        <UpArrowIcon v-if="dropdownOpen" />
        <DownArrowIcon v-else  />
        <div class="nav_dropdown_items absolute w-max" v-if="dropdownOpen" v-click-away="()=>dropdownOpen = false">
              <ul>
                  <li @click="logout">{{ $t('Log Out') }}</li>
              </ul>
          </div>
      </div>
    </div>
</div>

</template>

<script setup>
    import HamburgerIcon from '@/components/icons/Hamburger.vue';
    import LogoIcon from '@/components/icons/Logo.vue';
    import UpArrowIcon from '@/components/icons/UpArrowIcon.vue'
    import DownArrowIcon from '@/components/icons/DownArrowIcon.vue'

    import { useUserStore } from "@/store/user";
    import { ref } from 'vue';
    import axios from 'axios';
    import { useRouter } from 'vue-router'

    const router = useRouter()
    const userStore = useUserStore();
    const user = userStore.user;
    // console.log(user)
    const dropdownOpen = ref(false)

    const logout =  ()=> {
        axios.post(route('logout'))
        .then(res=>{
            location.reload()
        }).catch(err=>{
            console.log(err)
        })
    }

</script>

<style lang="scss" scoped>
    .nav_dropdown_items {
        top: 30px;
        right: 0;
        list-style: none;
        background: #FFFFFF;
        border: 1px solid #E6DEE5;
        border-radius: 10px;
        filter: drop-shadow(-2px 4px 14px rgba(91, 91, 91, 0.25));

        li {
            padding: 15px 24px;
            font-family: 'Inter';
            font-style: normal;
            font-weight: 500;
            font-size: 16px;
            line-height: 19px;

            color: #636363;
            cursor: pointer;
        }
    }
    .user_information {
        h4 {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 500;
            font-size: 16px;
            line-height: 19px;
            color: #383838;
        }

        p {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 500;
            font-size: 13px;
            line-height: 16px;
            color: #7E7E7E;

        }
    }
</style>
