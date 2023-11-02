<template>
  <p class="level text-[16px] leading-[19px] text-[#292929]">
    {{
      element.hierarchy_level === null ? $t("None") : element.hierarchy_level.toString()
    }}
  </p>
  <p class="name text-[16px] leading-[19px] text-[#292929]">{{ element.name }}</p>
  <div class="controls flex gap-[10px]">
    <PencilBoxIcon @click="editOrganizationalElementModalHandle" class="cursor-pointer"/>
    <EyeBoxIcon
      @click="openDetailsModal"
      @openEditModal="editOrganizationalElementModalHandle"
      class="cursor-pointer"
    />
  </div>
</template>

<script setup>
import PencilBoxIcon from "@/components/icons/PencilBoxIcon.vue";
import EyeBoxIcon from "@/components/icons/EyeBoxIcon.vue";
import HierarchyElementModificationModal from "./HierarchyElementModificationModal.vue";
import HierarchyElementDetailsModal from "./HierarchyElementDetailsModal.vue";
import { trans } from "laravel-vue-i18n";
import { inject } from "@vue/runtime-core";
import { computed } from "@vue/reactivity";
const props = defineProps({
  element: {
    type: Object,
    required: true,
  },
  hierarchy_levels: {
    type: Array,
    required: true,
  },
  used_subordinate_roles: {
    type: Array,
    required: true,
  },
});

const $vfm = inject("$vfm");
const emit = defineEmits(["onUpdate"]);

const hierarchy_levels = computed(() => {
  const levels = [...props.hierarchy_levels];
  if (props.element.hierarchy_level !== null) {
    levels.push({
      label: props.element.hierarchy_level,
      value: props.element.hierarchy_level,
    });
  }
  return levels.sort((a, b) => {
    if (a.value === null) return -1;
    return a.label < b.label ? -1 : 1;
  });
});

const editOrganizationalElementModalHandle = () => {
  const options = {
    component: HierarchyElementModificationModal,
    bind: {
      title: trans("Edit Organizational Element"),
      hierarchy_levels: hierarchy_levels.value,
      value: props.element,
      used_subordinate_roles: props.used_subordinate_roles,
    },
    on: {
      saveElement: (element) => {
        emit("onUpdate", element);
      },
    },
  };
  $vfm.show(options);
};

const openDetailsModal = () => {
  const options = {
    component: HierarchyElementDetailsModal,
    bind: {
      value: props.element,
    },
    on: {
      openEditModal: editOrganizationalElementModalHandle,
    },
  };
  $vfm.show(options);
};
</script>
