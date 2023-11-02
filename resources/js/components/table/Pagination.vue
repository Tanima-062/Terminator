<template>
    <div class="pagination__content">
      <ul class="pagination__body" v-if="show">
        <!-- First Page -->
        <li :class="{ disabled: !prev }" class="pagination-item first-page" v-if="prev">
          <a @click.prevent="goToPage(1)" class="pagination-link" href="#">
            <svg width="12" height="10" viewBox="0 0 12 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.08203 9.16732L0.998372 5.08366L5.08203 1" stroke="#636363" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10.8008 9.16732L6.71712 5.08366L10.8008 1" stroke="#636363" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </a>
        </li>

        <!-- Prev Page -->
        <li :class="{ disabled: !prev }" class="pagination-item prev-page" v-if="prev">
          <a @click.prevent="goToPage(pagination.current_page - 1)" class="pagination-link" href="#">
            {{ $t('Previous') }}
          </a>
        </li>

        <li
          v-for="(link, index) in links"
          :key="index"
          :class="{
            active: pagination.current_page == link,
            disabled: isNaN(link),
          }"
          class="pagination-item"
        >
          <a
            @click.prevent="goToPage(1)"
            class="pagination-link"
            :rel="getRelation(link)"
            href="#"
            v-if="link == 1"
          >
            {{ link }}
          </a>
          <a
            @click.prevent="goToPage(link)"
            class="pagination-link"
            :rel="getRelation(link)"
            :href="`?page=${link}`"
            v-else
          >
            {{ link }}
          </a>
        </li>

        <!-- Next Page -->
        <li :class="{ disabled: !next }" class="pagination-item next-page" v-if="next">
          <a href="#" @click.prevent="goToPage(next)" class="pagination-link">
            {{ $t('Next') }}
          </a>
        </li>

        <!-- Last Page -->
        <li :class="{ disabled: !next }" class="pagination-item last-page" v-if="next">
          <a
            @click.prevent="goToPage(pagination.last_page)"
            class="pagination-link"
            :href="`?page=${pagination.last_page}`"
          >
            <svg width="12" height="10" viewBox="0 0 12 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.71875 9.16732L10.8024 5.08366L6.71875 1" stroke="#636363" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M1 9.16732L5.08366 5.08366L1 1" stroke="#636363" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </a>
        </li>
      </ul>
    </div>
  </template>

  <script>
  export default {
    props: {
      pagination: {
        type: Object,
        required: true,
      },
    },
    data() {
      return {
        limit: 4,
        // q: {}
      };
    },

    computed: {
      pages() {
        let pages = [];
        for (let i = 1; i <= this.pagination.last_page; i++) {
          pages.push(i);
        }
        return pages;
      },
      links() {
        let first = [1, '...'],
          last = ['...', this.pagination.last_page],
          range = [];

        if (this.pagination.current_page <= this.limit) {
          range = this.range(1, this.limit + 1);
          return this.pagination.current_page + range.length <=
            this.pagination.last_page
            ? range.concat(last)
            : range;
        } else if (
          this.pagination.current_page >
          this.pagination.last_page - this.limit
        ) {
          range = this.range(
            this.pagination.last_page - this.limit,
            this.pagination.last_page
          );
          return this.pagination.current_page - range.length >= 1
            ? first.concat(range)
            : range;
        } else {
          range = this.range(
            this.pagination.current_page - Math.ceil(this.limit / 2),
            this.pagination.current_page + Math.ceil(this.limit / 2)
          );
          return first.concat(range).concat(last);
        }
      },
      next() {
        let next = this.pagination.current_page + 1;
        return next <= this.pagination.last_page ? next : false;
      },
      prev() {
        return this.pagination.current_page - 1;
      },
      show() {
        return this.pagination.last_page > 1;
      },
    },
    methods: {
      getRelation(link){
        if((this.pagination.current_page - 1) == link){
          return 'next'
        }
        else if((this.pagination.current_page  + 1 ) == link){
          return 'prev'
        }
        else if(this.pagination.current_page == link){
          return 'canonical'
        }else {
          return false;
        }
      },
      range(start, end) {
        let pages = [];

        for (let i = start - 1; i < end; i++) {
          if (this.pages[i]) {
            pages.push(this.pages[i]);
          }
        }
        return pages;
      },
      go(page) {
        if (isNaN(page)) {
          return;
        }
      },
      goToPage(link) {
        if(this.pagination.current_page == link) return;
        const query = { ...this.$route.query }
        query.page = link;

        this.$router.push({
          query: query,
        });
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        this.$emit('updateResult')
      },
    },
  };
  </script>


<style lang="scss" scoped>
    .pagination__content {
        display: flex;
        justify-content: center;
        width: 100%;
    }

    .pagination__body {
        margin: 0;
        padding: 0;

        list-style: none;
        display: flex;

        .pagination-item {
            margin-left: 5px;
            margin-right: 5px;

            &.active {
                .pagination-link {
                    pointer-events: none;
                    cursor: default;
                    color: #F8F1F1;
                    background: #AB326F;
                }
            }
            &.disabled {
                .pagination-link {
                    pointer-events: none;
                    cursor: default;
                }
            }
        }

        .pagination-item.prev-page {
            margin-right: 15px;
        }
        .pagination-item.next-page {
            margin-left: 15px;
        }
        .pagination-item.first-page {
            margin-right: 15px;
        }
        .pagination-item.last-page {
            // margin-right: 15px;
        }


        .pagination-link {
            display: block;
            // width: 34px;
            height: 34px;
            min-width: 34px;
            padding-left: 10px;
            padding-right: 10px;

            font-family: 'Inter';
            font-style: normal;
            font-weight: 500;
            font-size: 16px;
            line-height: 19px;
            border-radius: 11px;
            color: #535353;



            display: flex;
            align-items: center;
            text-align: center;
            justify-content: center;
            text-decoration: none;
        }
    }
</style>
