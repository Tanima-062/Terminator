<template>
  <div class="tr w-min" ref="row">
    <!-- <div style="width:2%">Checkbox</div> -->
    <template v-if="contact_data_record.contact_record_status == 'Duplicate' || contact_data_record.contact_record_status == 'Check Duplicate'">
      <div class="td w-[50px]"></div>
    </template>
    <template v-else>
      <CheckboxInput v-model="checked" checkLabel="" class="w-[50px]" />
    </template>
    <div class="td td-link" style="width: 110px" :style="{width: getWidth(userStore.user, '110px', '130px')}">
      <router-link :to="{ name: 'contact-data-records-show', params: { id: contact_data_record.id } }">
        {{ contact_data_record.last_appointment?.prefix_id }}
    </router-link>
    </div>
    <div class="td" style="width: 170px" :style="{width: getWidth(userStore.user, '170px', '190px')}">{{ contact_data_record.last_appointment? formateDate(contact_data_record.last_appointment.appointment_date) : '' }}</div>
    <div class="td" style="width: 170px" :style="{width: getWidth(userStore.user, '170px', '190px')}">{{ contact_data_record.last_appointment? formateTime(contact_data_record.last_appointment.appointment_time) : '' }}</div>
    <div class="td " style="width: 160px"   v-if=" userStore.user.type == 'broker_user' && userStore.user.role == 'Admin' ">
      <template v-if="contact_data_record.allocation">
        <template v-if="contact_data_record.allocation.type == 'Broker user'">
          <!-- <router-link :to="{ name: 'broker-user-show', params: { id: contact_data_record.allocation.broker_user_id } }">{{ contact_data_record.allocation.user.full_name }}</router-link> -->
          {{ contact_data_record.allocation.user.full_name }}
        </template>
        <template v-else-if="contact_data_record.allocation.type == 'Broker'">
          <!-- <router-link :to="{name: 'broker-show', params:{id: contact_data_record.allocation.broker.id}}">{{ contact_data_record.allocation.broker.name }}</router-link> -->
          {{ contact_data_record.allocation.broker.name }}
        </template>
      </template>
    </div>

    <div class="td" style="width: 225px" :style="{width: getWidth(userStore.user, '225px', '245px')}">{{ translateLanguageName(contact_data_record.correspondence_language) }}</div>
    <div class="td td-link" style="width: 160px" :style="{width: getWidth(userStore.user, '160px', '190px')}">
      <router-link :to="{ name: 'termins-show', params: { id: contact_data_record.id } }">{{ contact_data_record.first_name }}</router-link>
    </div>
    <div class="td td-link" style="width: 160px" :style="{width: getWidth(userStore.user, '160px', '190px')}">
      <router-link :to="{ name: 'termins-show', params: { id: contact_data_record.id } }">{{ contact_data_record.last_name }}</router-link>
    </div>

    <div class="td" style="width: 200px" :style="{width: getWidth(userStore.user, '200px', '210px')}">{{ $t(contact_data_record.contact_record_status) }}</div>
    <div class="td w-[35px]">
      <MenuBar :contact_data_record="contact_data_record" />
    </div>
  </div>
</template>

<script>
import MenuBar from './MenuBar.vue'
import Status from './Status.vue'
import CheckboxInput from '@/components/form/CheckboxInput.vue'
export default {
  props: {
    contact_data_record: {
      type: Object,
      required: true,
    },
    modelValue: {
      type: Boolean,
      default: false,
    },
  },
  components: {
    MenuBar,
    Status,
    CheckboxInput,
  },
  data() {
    return {
      checked: this.modelValue,
      status: this.contact_data_record.contact_record_status,
    }
  },
  computed: {
    position() {
      const row = this.$refs.row
      const rowPosition = row.getBoundingClientRect()
      const properties = {
        bottom: rowPosition.bottom,
        height: rowPosition.height,
        left: rowPosition.left,
        right: rowPosition.right,
        top: rowPosition.top,
        width: rowPosition.width,
        x: rowPosition.x,
        y: rowPosition.y,
      }

      console.log(properties)
      return properties
    },
  },
  watch: {
    checked() {
      this.$emit('update:modelValue', this.checked)
    },
    modelValue() {
      this.checked = this.modelValue
    },
  },

  mounted() {
    this.emitter.on(`update-status-${this.contact_data_record.id}`, (status) => {
      this.status = status
    })
  },
  methods: {
    getWidth(user, adminWidth, intermediaryWidth){
        // v-if=" userStore.user.type == 'broker_user' && userStore.user.role == 'Admin' "
        if(user.role == 'Admin') {
            return adminWidth
        }

        return intermediaryWidth;
    }
  }
}
</script>

<style lang="scss" scoped>
.tr {
  // width: calc(max-content + 50px);

  // transform: translateX(70px);
  // margin-left: -35px;
}
</style>
