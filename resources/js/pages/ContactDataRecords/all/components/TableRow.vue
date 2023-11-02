<template>
  <div class="tr w-min" ref="row">
    <!-- <div style="width:2%">Checkbox</div> -->
    <!-- <template v-if="(contact_data_record.contact_record_status == 'Duplicate' || contact_data_record.contact_record_status == 'Check Duplicate' || contact_data_record.category == 'lead')  ">
      <div class="td w-[50px]"></div>
    </template>
    <template v-else>
      <CheckboxInput v-model="checked" checkLabel="" class="w-[50px]" />
    </template> -->
    <template v-if="contact_data_record.category == 'lead' && contact_data_record.contact_record_status != 'Duplicate' && contact_data_record.contact_record_status != 'Check Duplicate'">
      <CheckboxInput v-model="checked" checkLabel="" class="w-[50px]" />
    </template>
    <template v-else>
      <div class="td w-[50px]"></div>
    </template>
    <div class="td td-link" style="width: 110px">
      <router-link :to="{ name: 'contact-data-records-show', params: { id: contact_data_record.id } }">
        {{ contact_data_record.prefix_id }}
    </router-link>
    </div>
    <div class="td" style="width: 110px">
        {{ contact_data_record.last_appointment?.prefix_id }}
    </div>
    <div class="td" style="width: 190px">{{ formateDate(contact_data_record.created_at) }}</div>
    <div class="td" style="width: 220px">{{ contact_data_record.category ? $t(contact_data_record.category) : '' }}</div>
    <div class="td td-link" style="width: 160px">
      <template v-if="contact_data_record.allocation">
        <template v-if="contact_data_record.allocation.type == 'Broker user'">
          <router-link :to="{ name: 'broker-user-show', params: { id: contact_data_record.allocation.broker_user_id } }">{{ contact_data_record.allocation.user.full_name }}</router-link>
        </template>
        <template
          v-else-if="
            contact_data_record.allocation.type == 'Leader Head of' ||
            contact_data_record.allocation.type == 'Manager' ||
            contact_data_record.allocation.type == 'Quality controller' ||
            contact_data_record.allocation.type == 'Call agent'
          "
        >
          <!-- 'Leader Head of', 'Manager', 'Quality controller', 'Call agent', -->
          <router-link :to="{ name: 'internal-user-show', params: { id: contact_data_record.allocation.internal_user_id } }">{{ contact_data_record.allocation.user.full_name }}</router-link>
        </template>
        <template v-else-if="contact_data_record.allocation.type == 'Broker'">
          <!-- <router-link :to="{name: 'broker-show', params:{id: contact_data_record.allocation.broker.id}}">{{ contact_data_record.allocation.broker.name }}</router-link> -->
        </template>
      </template>
    </div>
    <!-- <div class="td td-link" style="width: 160px">
      <template v-if="contact_data_record.allocation && contact_data_record.allocation.type == 'variableA'">
        <router-link :to="{ name: 'organization-element-show', params: { id: contact_data_record.allocation.organization_element_id } }">{{ contact_data_record.allocation.organization_element.name }}</router-link>
      </template>
    </div> -->
    <div class="td" style="width: 170px">{{ contact_data_record.campaign.name }}</div>
    <div class="td" style="width: 225px">{{ translateLanguageName(contact_data_record.correspondence_language) }}</div>

    <div class="td td-link" style="width: 160px">
      <router-link :to="{ name: 'contact-data-records-show', params: { id: contact_data_record.id } }">{{ contact_data_record.first_name }}</router-link>
    </div>
    <div class="td td-link" style="width: 160px">
      <router-link :to="{ name: 'contact-data-records-show', params: { id: contact_data_record.id } }">{{ contact_data_record.last_name }}</router-link>
    </div>
    <!-- <div class="td" style="width: 150px">{{ contact_data_record.zip_code }}</div>
    <div class="td" style="width: 200px">{{ contact_data_record.city }}</div>

    <div class="td" style="width: 280px">{{ contact_data_record.number_of_persons_in_household }}</div> -->
    <div class="td" style="width: 225px">{{ contact_data_record.lead ? $t(contact_data_record.lead) : '' }}</div>

    <div class="td" style="width: 270px">{{ contact_data_record.last_appointment? contact_data_record.last_appointment.control_status_appointment: '' }}</div>


    <div class="td" style="width: 270px">{{ $t(contact_data_record.contact_record_status) }}</div>
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

    //   console.log(properties)
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
}
</script>

<style lang="scss" scoped>
.tr {
  // width: calc(max-content + 50px);

  // transform: translateX(70px);
  // margin-left: -35px;
}
</style>
