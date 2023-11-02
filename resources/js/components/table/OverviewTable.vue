<template>
  <div class="overview__table" v-if="paginationData.total">
    <div class="overview__table--header__top flex items-center h-[70px]" :class="[$attrs.headerTopClass]" v-if="headerTop">
      <slot name="header-top" />
    </div>
    <div class="overview__table--header flex" :class="[$attrs.headerClass]" ref="header" :style="style">
      <slot name="header" />
    </div>
    <div class="overview__table--body" style="max-height: 597px; height: 597px" ref="overview__table__body" @scroll="scrollBody" :style="bodyStyle">
      <!-- <div class="overview__table--header flex">
                <slot name="header" />
            </div> -->
      <slot name="body" />
    </div>
    <div class="overview__table--footer flex justify-between">
      <div class="per__page flex items-center gap-x-2.5">
        <h4 class="entries_per_page">{{ $t('Entries Per Page') }}</h4>
        <div
          class="per__page--input relative flex items-center justify-between px-3 py-1.5 rounde-[2px] border border-solid border-[#E6DEE5] h-[30px] w-[75px] cursor-pointer"
          @click="openPerPageDropdown = !openPerPageDropdown"
        >
          {{ per_page }}
          <UpArrowIcon v-if="openPerPageDropdown" />
          <DownArrowIcon v-else />
          <ul class="per__page--dropdown absolute" v-show="openPerPageDropdown" v-click-away="() => (openPerPageDropdown = false)">
            <li @click.stop="setPerPage(10)">10</li>
            <li @click.stop="setPerPage(25)">25</li>
            <li @click.stop="setPerPage(50)">50</li>
            <li @click.stop="setPerPage(100)">100</li>
            <li @click.stop="setPerPage(250)">250</li>
          </ul>
        </div>
      </div>
      <div class="pagination">
        <Pagination :pagination="paginationData" />
      </div>
    </div>
  </div>
  <NoResultFound v-else />
</template>

<script>
import DownArrowIcon from '../icons/DownArrowIcon.vue'
import UpArrowIcon from '../icons/UpArrowIcon.vue'
import Pagination from './Pagination.vue'
import NoResultFound from './NoResultFound.vue'

export default {
  components: { DownArrowIcon, UpArrowIcon, Pagination, NoResultFound },
  props: {
    paginationData: {
      type: Object,
      required: true,
    },
    headerTop: {
      type: Boolean,
      default: false,
    },
    bodyStyle: {
        type: String,
        default: ''
    }
  },
  data() {
    return {
      openPerPageDropdown: false,
      per_page: null,
      hasScrollableContent: false,
    }
  },
  created() {
    const per_page = this.$route.query.per_page
    this.per_page = per_page ?? 25
  },
  watch: {
    paginationData: {
      handler(newVal) {
        this.hasScrollableContent = newVal.to - newVal.from > 7 ? true : false
      },
      deep: true,
    },
  },
  computed: {
    style() {
      return this.hasScrollableContent ? `width: calc(100% - 14px);` : `width: 100%`
    },
  },
  methods: {
    setPerPage(perPage) {
      this.openPerPageDropdown = false
      this.per_page = perPage

      const queries = this.$route.query

      delete queries.page
      this.$router.push({ query: { ...queries, per_page: perPage } })
    },
    scrollBody(e) {
      const left = e.target.scrollLeft
      // this.$refs.header.scrollLeft = left
    },
  },
}
</script>

<style lang="scss" scoped>
.per__page--input {
  font-family: 'Inter';
  font-style: normal;
  font-weight: 500;
  font-size: 14px;
  line-height: 17px;
  color: #636363;

  svg {
    // margin-left: 14px;
  }
}

.per__page--dropdown {
  list-style: none;

  top: 28px;
  left: 0;
  width: 100%;
  background: #ffffff;
  border: 1px solid #e6dee5;
  border-radius: 0px 0px 2px 2px;
  li {
    font-family: 'Inter';
    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    line-height: 17px;
    color: #636363;
    padding: 6px 12px;

    border-bottom: 1px solid #e6dee5;

    &:last-child {
      border-bottom: none;
    }
  }
}

.entries_per_page {
  font-family: 'Inter';
  font-style: normal;
  font-weight: 500;
  font-size: 14px;
  line-height: 17px;
  color: #636363;
}
</style>
