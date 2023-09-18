<template>
  <div :class="className">
    <template v-for="(menu, i) in menus">
      <div
        :key="menu.label + i"
        v-if="menu.type === 'product'"
        class="navbar-item has-dropdown is-hoverable"
        style="background-color: inherit;"
      >
        <a class="navbar-link" href="#" style="background-color: transparent;">
          {{ menu.label }}
        </a>

        <div class="navbar-dropdown" :style="{'background-color': menu.backgroundColor}">
          <a href="#" class="navbar-item" :style="{'color': menu.color}">
            Semua Kategori
          </a>

          <div class="nested navbar-item dropdown">
            <div class="dropdown-trigger">
              <a
                href="#"
                :style="{'color': menu.color}"
                aria-haspopup="true"
                aria-controls="dropdown-menu"
              >
                <span>Kategori 1</span>
              </a>
            </div>
            <div class="dropdown-menu" id="dropdown-menu" role="menu">
              <div class="dropdown-content" :style="{'background-color': menu.backgroundColor}">
                <a href="#" class="dropdown-item" :style="{'background-color': menu.backgroundColor, 'color': menu.color,}"> Produk 1 </a>
                <a href="#" class="dropdown-item" :style="{'background-color': menu.backgroundColor, 'color': menu.color,}"> Produk 2 </a>
              </div>
            </div>
          </div>

          <div class="nested navbar-item dropdown">
            <div class="dropdown-trigger">
              <a
                href="#"
                :style="{'color': menu.color}"
                aria-haspopup="true"
                aria-controls="dropdown-menu"
              >
                <span>Kategori 2</span>
              </a>
            </div>
            <div class="dropdown-menu" id="dropdown-menu" role="menu">
              <div class="dropdown-content" :style="{'background-color': menu.backgroundColor}">
                <a href="#" class="dropdown-item" :style="{'background-color': menu.backgroundColor, 'color': menu.color,}"> Produk 1 </a>
                <a href="#" class="dropdown-item" :style="{'background-color': menu.backgroundColor, 'color': menu.color,}"> Produk 2 </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <a
        :key="menu.label + i"
        v-if="menu.type !== 'product'"
        class="navbar-item"
        :class="{
          'is-active': menu.id == editing,
        }"
        :href="menu.url"
        @click="changePage(menu)"
      >
        {{ menu.label }}
      </a>
    </template>
  </div>
</template>

<script>
export default {
  name: "navbar-menu",
  props: {
    className: String,
  },
  computed: {
    editing() {
      return this.$store.state.editing;
    },
    menus() {
      return this.$store.state.project.menus.filter(
        (menu) =>
          !menu.hidden &&
          menu.id !== this.$store.state.project.post_single &&
          menu.id !== this.$store.state.project.catalog_single
      );
    },
  },
  methods: {
    changePage(menu) {
      if (menu.type == "page") return this.$store.commit("editing", menu.id);

      return false;
    },
  },
};
</script>
