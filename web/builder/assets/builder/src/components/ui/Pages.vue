<template>
  <div class="icl-editor__page icl-editor__tab">
    <div class="icl-editor__page-content">
      <div class="page-list p-3">
        <div class="mb-4">
          <h3 class="panel-title">
            <i class="mdi mdi-insert_drive_file" /> Halaman & Menu
          </h3>
          <p>
            Anda juga dapat mengatur halaman yang ingin ditampilkan pada menu di
            sini
          </p>
        </div>
        <div class="page-quota">
          <div class="d-flex justify-content-between mb-2">
            <span>Kuota halaman mu saat ini</span>
            <span>{{ pageCount }} dari {{ quota }}</span>
          </div>
          <div class="page-quota-progress">
            <div
              class="page-quota-progress-bar"
              :style="{ width: `${quotaProgress}%` }"
            />
          </div>
        </div>
        <div class="btn-group pages-action">
          <button class="btn btn-outline-secondary" @click="addPage">
            <i class="mdi mdi-insert_drive_file" /> Halaman baru
          </button>

          <button
            class="btn btn-outline-secondary"
            @click="addLink"
            title="Link yang ditampilkan otomatis di hampir semua block header">
            <i class="mdi mdi-link" /> Link baru
          </button>

          <button
            class="btn btn-outline-secondary"
            @click="addDropdownMenu"
            title="Drop down menu yang ditampilkan otomatis di hampir semua block header">
            <i class="mdi mdi-menu" /> Dropdown menu
          </button>
        </div>

        <draggable
          v-model="menus"
          class="site-menu"
          handle=".page-item__header"
        >
          <div
            v-for="menu in menus"
            :key="menu.id"
            class="page-item"
            :class="{
              'is-active':
                menu.type === 'page' && parseInt(menu.id) === editing,
              'is-hidden': menu.hidden,
            }"
          >
            <div class="page-item__header" @click="menuClick(menu)">
              <div class="menu-icon">
                <i
                  class="mdi mdi-home"
                  v-if="menu.type == 'page' && menu.id == project.homepage"
                ></i>
                <i
                  class="mdi mdi-insert_drive_file"
                  v-if="menu.type == 'page' && menu.id !== project.homepage"
                ></i>
                <i class="mdi mdi-link" v-if="menu.type == 'link'"></i>
                <i class="mdi mdi-menu" v-if="menu.type == 'product'"></i>
              </div>
              <span class="menu-title">
                {{ menu.label }}
                <span v-if="menu.hidden" class="ml-2 badge badge-secondary"
                  >Disembunyikan</span
                >
                <span
                  v-if="menu.id === $store.state.project.post_index"
                  class="ml-2 badge badge-secondary"
                  >Daftar Artikel</span
                >
                <span
                  v-if="menu.id === $store.state.project.post_single"
                  class="ml-2 badge badge-secondary"
                  >Detail Artikel</span
                >
                <span
                  v-if="menu.id === $store.state.project.catalog_index"
                  class="ml-2 badge badge-secondary"
                  >Daftar Katalog</span
                >
                <span
                  v-if="menu.id === $store.state.project.catalog_single"
                  class="ml-2 badge badge-secondary"
                  >Detail Katalog</span
                >
              </span>
              <div class="d-flex align-items-center">
                <button
                  v-if="menu.type == 'page'"
                  data-tooltip="Duplikasi Halaman"
                  @click.prevent.stop="duplicatePage(menu.id)"
                >
                  <i class="mdi mdi-content_copy"></i>
                </button>
                <button
                  :data-tooltip="
                    menu.type == 'page' ? 'Atur Halaman' : 'Atur Menu'
                  "
                  @click.prevent.stop="setActiveMenu(menu.menuId)"
                >
                  <i class="mdi mdi-settings"></i>
                </button>
                <button
                  v-if="menu.type == 'page'"
                  data-tooltip="Personalisasi"
                  @click.prevent.stop="openPagePersonalization(menu.menuId);"
                >
                  <i class="mdi mdi-manage_accounts"></i>
                </button>
              </div>
            </div>
          </div>
        </draggable>
      </div>
      <transition name="pull-left">
        <!-- menu setting -->
        <div class="page-options" v-if="editorState.selectedMenu && !editorState.openPagePersonalization">
          <div class="page-options-header">
            <button @click="setActiveMenu(null)">
              <i class="mdi mdi-arrow_back"></i>
            </button>
          </div>
          <div class="page-options-fields" v-if="menuOptions.type == 'page'">
            <div class="form-group">
              <label class="form-label">Teks menu</label>
              <input
                class="form-control"
                type="text"
                v-model="menuOptions.label"
              />
            </div>
            <div class="form-group">
              <label class="form-label">Judul</label>
              <input
                class="form-control"
                type="text"
                v-model="menuOptions.title"
              />
            </div>
            <div class="form-group">
              <label class="form-label">Deskripsi</label>
              <textarea
                class="form-control"
                v-model="menuOptions.description"
              ></textarea>
            </div>
            <div class="form-group">
              <label class="form-label">URL/Slug</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">/</div>
                </div>
                <input
                  v-if="menuOptions.id == project.homepage"
                  class="form-control"
                  type="text"
                  disabled
                />
                <input
                  v-else
                  class="form-control"
                  type="text"
                  v-model="menuOptions.slug"
                />
              </div>
            </div>
            <div
              class="form-group d-flex flex-row justify-content-between align-items-center"
            >
              <div class="form-label">Sembunyikan dari menu?</div>
              <div class="field-switch">
                <label>
                  <input type="checkbox" v-model="menuOptions.hidden" />
                  <span class="field-switch-ui"></span>
                </label>
              </div>
            </div>

            <div class="page-type">
              <label>Atur halaman sebagai:</label>
              <div class="page-type-options">
                <button
                  class="btn btn-outline-secondary"
                  :class="{ 'is-active': menuOptions.id == project.homepage }"
                  @click="setHomepage(menuOptions.id)"
                >
                  <i class="mdi mdi-home"></i><strong>Beranda</strong>
                </button>
                <!-- <button
                  class="btn btn-outline-secondary"
                  :class="{
                    'is-active':
                      menuOptions.id !== project.homepage &&
                      menuOptions.id == project.post_index,
                  }"
                  @click="setPostIndex(menuOptions.id)"
                >
                  <i class="mdi mdi-art_track"></i
                  ><strong>Daftar Artikel</strong>
                </button>
                <button
                  class="btn btn-outline-secondary"
                  :class="{
                    'is-active':
                      menuOptions.id !== project.homepage &&
                      menuOptions.id == project.post_single,
                  }"
                  @click="setPostSingle(menuOptions.id)"
                >
                  <i class="mdi mdi-description"></i
                  ><strong>Detail Artikel</strong>
                </button>
                <button
                  class="btn btn-outline-secondary"
                  :class="{
                    'is-active':
                      menuOptions.id !== project.homepage &&
                      menuOptions.id == project.catalog_index,
                  }"
                  @click="setCatalogIndex(menuOptions.id)"
                >
                  <i class="mdi mdi-store"></i><strong>Daftar Katalog</strong>
                </button>
                <button
                  class="btn btn-outline-secondary"
                  :class="{
                    'is-active':
                      menuOptions.id !== project.homepage &&
                      menuOptions.id == project.catalog_single,
                  }"
                  @click="setCatalogSingle(menuOptions.id)"
                >
                  <i class="mdi mdi-receipt"></i><strong>Detail Katalog</strong>
                </button> -->
              </div>
            </div>

            <button
              v-if="isTemplate"
              class="set-homepage btn btn-secondary"
              @click="setAsLandingPage(menuOptions.id)"
            >
              <i class="mdi mdi-home"></i>Jadikan Landing Page Template
            </button>
          </div>
          <div class="page-options-fields" v-else-if="menuOptions.type == 'link'">
            <div class="form-group">
              <label class="form-label">Menu Label</label>
              <input
                class="form-control"
                type="text"
                v-model="menuOptions.label"
              />
            </div>
            <div class="form-group">
              <label class="form-label">URL/Slug</label>
              <div class="input-group">
                <input
                  class="form-control"
                  type="text"
                  v-model="menuOptions.url"
                />
                <div class="input-group-append">
                  <span
                    @click="openLinkBuilder(menuOptions.url)"
                    class="build-link input-group-text"
                    ><i class="mdi mdi-link"></i> Link Builder</span
                  >
                </div>
              </div>
            </div>
            <div
              class="form-group d-flex flex-row justify-content-between align-items-center"
            >
              <div class="form-label">Open in new tab?</div>
              <div class="field-switch">
                <label>
                  <input type="checkbox" v-model="menuOptions.new_tab" />
                  <span class="field-switch-ui"></span>
                </label>
              </div>
            </div>
            <div
              class="form-group d-flex flex-row justify-content-between align-items-center"
            >
              <div class="form-label">Sembunyikan dari menu?</div>
              <div class="field-switch">
                <label>
                  <input type="checkbox" v-model="menuOptions.hidden" />
                  <span class="field-switch-ui"></span>
                </label>
              </div>
            </div>
          </div>
          <div class="page-options-fields" v-else>
            <div class="form-group">
              Drop down menu untuk katalog dan produk. Pastikan halaman untuk Daftar Katalog dan Detail Katalog sudah dibuat.
            </div>

            <div class="form-group">
              <label class="form-label">Menu Label</label>
              <input
                class="form-control"
                type="text"
                v-model="menuOptions.label"
              />
            </div>

            <div class="form-group">
              <label class="form-label">Kategori</label>
              <select class="form-control" v-model="menuOptions.category_id">
                <option :value="0">Semua Kategori</option>
                <option
                  v-for="option in $store.state.project.catalog_category"
                  :key="option.id"
                  :value="option.id">
                    {{option.title}}
                </option>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Warna Teks Dropdown</label>

              <ColorPicker :vModel="menuOptions.color" v-on:changeColor="updateColor($event, 'color')" />
            </div>

            <div class="form-group">
              <label class="form-label">Warna Background Dropdown</label>

              <ColorPicker :vModel="menuOptions.backgroundColor" v-on:changeColor="updateColor($event, 'backgroundColor')" />
            </div>

            <div
              class="form-group d-flex flex-row justify-content-between align-items-center"
            >
              <div class="form-label">Sembunyikan dari menu?</div>
              <div class="field-switch">
                <label>
                  <input type="checkbox" v-model="menuOptions.hidden" />
                  <span class="field-switch-ui"></span>
                </label>
              </div>
            </div>
          </div>

          <div class="page-options-actions">
            <button
              v-if="menuOptions.type == 'page'"
              class="btn btn-outline-danger"
              @click.stop="deletePage(menuOptions.id)"
            >
              <i class="mdi mdi-delete"></i> Hapus
            </button>
            <button
              v-if="menuOptions.type == 'link' || menuOptions.type == 'product'"
              class="btn btn-outline-danger"
              @click.stop="removeLink(menuOptions.menuId)"
            >
              Hapus
            </button>
            <button
              v-if="menuOptions.type == 'page'"
              class="btn btn-outline-secondary"
              @click.stop="saveAsTemplate(menuOptions.id)"
            >
              <i class="mdi mdi-format_paint"></i> Simpan sebagai template
            </button>
          </div>
        </div>
        
        <!-- page personalization setting -->
        <div class="page-options" v-if="editorState.selectedMenu && editorState.openPagePersonalization && menuOptions.type == 'page'">
          <div class="icl-editor__block-options-header">
            <button @click="openPagePersonalization(null)">
              <i class="mdi mdi-arrow_back"></i>
            </button>
            <h2>Personalisasi: {{menuOptions.label}}</h2>
          </div>

          <div class="page-option-domain">
            <div :class="{'is-active': editorState.activePersonalizationTab == 'user-tag'}" @click="setActivePersonalizationTab('user-tag')"><i class="mdi mdi-assignment_ind"></i><div>User tag</div></div>
            <div :class="{'is-active': editorState.activePersonalizationTab == 'user-tag2'}" @click="setActivePersonalizationTab('user-tag2')"><i class="mdi mdi-rule"></i><div>Rules</div></div>
          </div>

          <div class="page-options-fields" v-if="editorState.activePersonalizationTab == 'user-tag'">
            <div v-for="(item, key) in personalization" :key="key">
              <div class="pt-4 py-1 px-4">
                <h6>{{item.label}}</h6>
              </div>
              <div class="block-option__field form-group py-2" v-for="(option, index) in item.options" :key="index">
                <div class="icl-ed-field icl-ed-field--horizontal">
                  <label class="form-label">{{option}}</label>
                  <div class="field-switch">
                    <label>
                      <input type="checkbox" :checked="checkPersonalizationUserTag(item.label, option)" @change="changePersonalizationUserTag($event, item.label, option)">
                      <span class="field-switch-ui"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="page-options-fields" v-if="editorState.activePersonalizationTab == 'user-tag2'">
            <component :is="`field-personalization-rules`" :data="computedPersonalizationRules" ></component>
          </div>

        </div>
      </transition>

      <div
        class="page-loading"
        v-if="loading"
        style="position: absolute; top: 0; left:0; right: 0; bottom: 0; background-color:rgba(255,255,255,.7);display:flex;align-items: center;justify-content: center"
      >
        <div class="lds-grid">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import draggable from "vuedraggable";
import qs from "qs";
import Axios from "axios";
import randomId from "random-id";
import findIndex from "lodash/findIndex";
import debounce from "lodash/debounce";
import cloneDeep from "lodash/cloneDeep";
import ColorPicker from "./pages/ColorPicker";
import fieldPersonalizationRules from '@/components/fields/PersonalizationRules';

export default {
  name: "Pages",
  components: {
    draggable,
    ColorPicker,
    'field-personalization-rules': fieldPersonalizationRules,
  },
  data() {
    return {
      activeMenu: null,
      loading: false
    };
  },
  mounted() {
    this.$store.subscribe((mutation) => {
      if (mutation.type == "activeSidebarTab") {
        this.$store.commit("selectedMenu", null);
        this.$store.commit("openPagePersonalization", false);
      }
    });
  },
  computed: {
    disabledDropdownMenu() {
      return this.project.catalog_index && this.project.catalog_single;
    },
    header01Block() {
      return this.editorState.blocks.find((block) => block.blockID === "header-01");
    },
    footer01Block() {
      return this.editorState.blocks.find((block) => block.blockID === "footer-01");
    },
    indexCatalogBlock() {
      return this.editorState.blocks.find((block) => block.blockID === "catalog-01");
    },
    detailCatalogBlock() {
      return this.editorState.blocks.find((block) => block.blockID === "catalog-02");
    },
    indexArticleBlock() {
      return this.editorState.blocks.find((block) => block.blockID === "post-01");
    },
    detailArticleBlock() {
      return this.editorState.blocks.find((block) => block.blockID === "post-04");
    },
    isTemplate() {
      return this.$root.config.extra.is_template;
    },
    quota() {
      return this.$store.state.project.quota;
    },
    pageCount() {
      if (this.$store.state.project.pages)
        return this.$store.state.project.pages.length;
      else return 0;
    },
    editorState() {
      return this.$store.state.editorState;
    },
    quotaProgress() {
      return (this.pageCount / this.quota) * 100;
    },
    editing() {
      return this.$store.state.editing;
    },
    project() {
      return this.$store.state.project;
    },
    menus: {
      get() {
        return this.$store.state.project.menus;
      },
      set(value) {
        return this.$store.commit("siteMenu", value);
      },
    },
    menuOptions() {
      const index = findIndex(
        this.menus,
        (i) => i.menuId == this.editorState.selectedMenu
      );
      return this.menus[index];
    },
    personalization() {
      return this.project.personalization;
    },
    computedPersonalizationRules() {
      const index = findIndex(this.project.pages, i=> i.id == this.menuOptions.id);
      if (index !== -1){
        const page = this.project.pages[index];
        const {id, label, title, slug, description} = page;
        return {
          item_title: 'title',
          personalization: this.personalization,
          pagePersonalizationRules: page.personalization.rules,
          page: {id, label, title, slug, description}
        };
      }
      else {
        return {};
      }
    }
  },
  watch: {
    menus: {
      handler(after) {
        this.$store.commit('dirty', true);
        if (this.editorState.selectedMenu !== null) {
          const index = findIndex(
            this.menus,
            (i) => i.menuId == this.editorState.selectedMenu
          );
          if (this.menus[index].type == "page") {
            this.updatePageMeta(after[index]);
          }
        }
        return this.updateMenu(after);
      },
      deep: true,
    },
  },
  methods: {
    updateColor(event, property) {
      if (property === "color") {
        this.menuOptions.color = event;
      } else {
        this.menuOptions.backgroundColor = event;
      }
    },
    updateMenu: debounce(function(value) {
      this.$store.commit("siteMenu", value);
    }, 300),
    updatePageMeta: debounce(function(value) {
      this.$store.commit("dirty", true);
      this.$store.commit("updatePageMeta", value);
    }, 300),
    addPage() {
      const quota = this.$store.state.project.quota;
      const currentPages = this.$store.state.project.pages.length;

      if (currentPages >= quota)
        return this.$swal.fire({
          title: "Kuota halaman anda sudah habis",
          text: "Silahkan hapus sebagian halaman dan buat halaman baru lagi",
          type: "info",
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Ok",
        });

      return this.$templateModal.show({
        onSelected: this.onSelectedTemplate,
      });
    },
    dispatchAddPage(data) {
      this.$store
        .dispatch("addPage", cloneDeep(data))
        .then(() => {
          this.loading = false;
          this.$nprogress.done();
          return this.$notify({
            group: "builder",
            title: "Halaman baru telah dibuat",
            text: `Halaman baru telah ditambahkan`,
            type: "success",
          });
        })
        .catch((error) => {
          this.loading = false;
          this.$nprogress.done();
          return this.$notify({
            group: "builder",
            title: "Gagal menambahkan halaman",
            text: `Terjadi kesalahan ketika membuat halaman.\n ${error}`,
            type: "warning",
          });
        });
    },
    onSelectedTemplate(data) {
      if (data.title == "__blank-template")
        data = {
          title: "New Page",
          description: "Page description",
          label: "New page",
          blocks: [],
          slug: "new-page",
        };
      
      if ( data.title == "product-template" ) {
        data = [
          {
            'title'       : 'Daftar Produk',
            'description' : 'Deskripsi Daftar Produk',
            'label'       : 'Daftar Produk',
            'blocks'      : [
              {
                ...this.header01Block,
                data: {
                  ...this.header01Block.data,
                  logo: {
                    description: this.header01Block.data.logo.description,
                    value: this.$store.state.project.logo,
                  }
                },
                id: this.$uuid.v1(),
                name: this.header01Block.title,
              },
              {
                ...this.indexCatalogBlock,
                id: this.$uuid.v1(),
                name: this.indexCatalogBlock.title,
              },
              {
                ...this.footer01Block,
                data: {
                  ...this.footer01Block.data,
                  logo: {
                    description: this.footer01Block.data.logo.description,
                    value: this.$store.state.project.logo,
                  }
                },
                id: this.$uuid.v1(),
                name: this.footer01Block.title,
              },
            ],
            'slug'        : 'daftar-produk-ukm'
          },
          {
            'title'       : 'Detail Produk',
            'description' : 'Deskripsi Detail Produk',
            'label'       : 'Detail Produk',
            'blocks'      : [
              {
                ...this.header01Block,
                data: {
                  ...this.header01Block.data,
                  logo: {
                    description: this.header01Block.data.logo.description,
                    value: this.$store.state.project.logo,
                  }
                },
                id: this.$uuid.v1(),
                name: this.header01Block.title,
              },
              {
                ...this.detailCatalogBlock,
                id: this.$uuid.v1(),
                name: this.detailCatalogBlock.title,
              },
              {
                ...this.footer01Block,
                data: {
                  ...this.footer01Block.data,
                  logo: {
                    description: this.footer01Block.data.logo.description,
                    value: this.$store.state.project.logo,
                  }
                },
                id: this.$uuid.v1(),
                name: this.footer01Block.title,
              },
            ],
            'slug'        : 'detail-produk-ukm'
          },
        ];
      }

      if ( data.title == "article-template" ) {
        data = [
          {
            'title'       : 'Daftar Artikel',
            'description' : 'Deskripsi Daftar Artikel',
            'label'       : 'Daftar Artikel',
            'blocks'      : [
              {
                ...this.header01Block,
                data: {
                  ...this.header01Block.data,
                  logo: {
                    description: this.header01Block.data.logo.description,
                    value: this.$store.state.project.logo,
                  }
                },
                id: this.$uuid.v1(),
                name: this.header01Block.title,
              },
              {
                ...this.indexArticleBlock,
                id: this.$uuid.v1(),
                name: this.indexArticleBlock.title,
              },
              {
                ...this.footer01Block,
                data: {
                  ...this.footer01Block.data,
                  logo: {
                    description: this.footer01Block.data.logo.description,
                    value: this.$store.state.project.logo,
                  }
                },
                id: this.$uuid.v1(),
                name: this.footer01Block.title,
              },
            ],
            'slug'        : 'daftar-artikel-ukm'
          },
          {
            'title'       : 'Detail Artikel',
            'description' : 'Deskripsi Detail Artikel',
            'label'       : 'Detail Artikel',
            'blocks'      : [
              {
                ...this.header01Block,
                data: {
                  ...this.header01Block.data,
                  logo: {
                    description: this.header01Block.data.logo.description,
                    value: this.$store.state.project.logo,
                  }
                },
                id: this.$uuid.v1(),
                name: this.header01Block.title,
              },
              {
                ...this.detailArticleBlock,
                id: this.$uuid.v1(),
                name: this.detailArticleBlock.title,
              },
              {
                ...this.footer01Block,
                data: {
                  ...this.footer01Block.data,
                  logo: {
                    description: this.footer01Block.data.logo.description,
                    value: this.$store.state.project.logo,
                  }
                },
                id: this.$uuid.v1(),
                name: this.footer01Block.title,
              },
            ],
            'slug'        : 'detail-artikel-ukm'
          },
        ];
      }

      if (data.type == "user-template") {
        // return console.log( data );
        data = {
          title: data.name,
          description: "Page description",
          label: data.name,
          blocks: data.blocks,
          slug: "new-page",
        };
        delete data.id;
      }

      // return console.log(cloneDeep(data));
      this.$store.commit("dirty", true);
      this.loading = true;
      this.$nprogress.start();

      // if there are more than 1 page (for example: product-page) (array)
      if (data.length > 1) {
        const produkAlreadyThere = this.project.pages.find((page) => page.slug.includes("produk-ukm"));
        const artikelAlreadyThere = this.project.pages.find((page) => page.slug.includes("artikel-ukm"));

        if (data[0].slug.includes("produk-ukm") && produkAlreadyThere) {
          // if there are already product pages
          this.loading = false;
          this.$nprogress.done();
          return this.$notify({
            group: "builder",
            title: "Gagal menambahkan halaman",
            text: "Halaman Daftar Produk dan Detail Produk sudah ada",
            type: "error",
          });
        }
        
        if (data[0].slug.includes("artikel-ukm") && artikelAlreadyThere) {
          this.loading = false;
          this.$nprogress.done();
          return this.$notify({
            group: "builder",
            title: "Gagal menambahkan halaman",
            text: "Halaman Daftar Artikel dan Detail Artikel sudah ada",
            type: "error",
          });
        }

        // if there are no article pages or product pages
        for (let i = 0; i < data.length; i++) {
          this.dispatchAddPage(data[i]);
        }
      } else {
        // single page (object)
        this.dispatchAddPage(data);
      }
    },
    duplicatePage(id) {
      const index = findIndex(
        this.$store.state.project.pages,
        (i) => i.id == id
      );
      const data = cloneDeep(this.$store.state.project.pages[index]);
      const quota = this.$store.state.project.quota;
      const currentPages = this.$store.state.project.pages.length;

      if (currentPages >= quota)
        return this.$swal.fire({
          title: "Kuota halaman anda sudah habis",
          text: "Silahkan hapus sebagian halaman dan buat halaman baru lagi",
          type: "info",
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Ok",
        });

      delete data.id;

      this.$store.commit("dirty", true);
      this.loading = true;
      this.$nprogress.start();
      return this.$store
        .dispatch("addPage", data)
        .then(() => {
          this.loading = false;
          this.$nprogress.done();
          return this.$notify({
            group: "builder",
            title: "Duplikasi halaman berhasil",
            text: `Halaman berhasil diduplikasi`,
            type: "success",
          });
        })
        .catch((error) => {
          this.loading = false;
          this.$nprogress.done();
          return this.$notify({
            group: "builder",
            title: "Gagal menduplikasi halaman",
            text: `Terjadi kesalahan ketika menduplikasi halaman.\n ${error}`,
            type: "warning",
          });
        });
    },
    dispatchDeletePage(id) {
      this.$store.commit("dirty", true);
      this.loading = true;
      this.$nprogress.start();
      this.$store
        .dispatch("deletePage", id)
        .then(() => {
          this.loading = false;
          this.$nprogress.done();
          return this.$notify({
            group: "builder",
            title: "Berhasil menghapus halaman",
            text: `Halaman berhasil dihapus dari situs ada`,
            type: "success",
          });
        })
        .catch((error) => {
          this.loading = false;
          this.$nprogress.done();
          return this.$notify({
            group: "builder",
            title: "Gagal menghapus halaman",
            text: `Terjadi kesalahan ketika menghapus halaman.\n ${error}`,
            type: "warning",
          });
        });
    },
    deletePage(id) {
      let last2Page = '';
      let otherOne = null;

      // Check if it's index product page
      if (id === this.project.catalog_index) {
        otherOne = this.project.catalog_single;
        if (this.$store.state.project.pages.length === 2) {
          last2Page = 'Produk';
        }
      }

      // Check if it's single product page
      if (id === this.project.catalog_single) {
        otherOne = this.project.catalog_index;
        if (this.$store.state.project.pages.length === 2) {
          last2Page = 'Produk';
        }
      }

      // Check if it's index article page
      if (id === this.project.post_index) {
        otherOne = this.project.post_single;
        if (this.$store.state.project.pages.length === 2) {
          last2Page = 'Artikel';
        }
      }

      // Check if it's single article page
      if (id === this.project.post_single) {
        otherOne = this.project.post_index;
        if (this.$store.state.project.pages.length === 2) {
          last2Page = 'Artikel';
        }
      }

      // Check if it's the last page
      let lastPage = this.$store.state.project.pages.length === 1;

      if (lastPage)
        return this.$swal.fire({
          title: "Tidak dapat menghapus halaman terakhir",
          text:
            "Halaman terakhir tidak bisa dihapus. Silakan tambah halaman baru sebelum menghapus halaman ini.",
          type: "info",
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Ok",
        });

      if (last2Page)
        return this.$swal.fire({
          title: "Tidak dapat menghapus 2 halaman terakhir",
          text:
            `Jika halaman Daftar ${last2Page} dihapus, maka halaman Detail ${last2Page} juga akan terhapus, begitu juga sebaliknya. Silakan tambah halaman baru sebelum menghapus halaman ini.`,
          type: "info",
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Ok",
        });

      this.$swal({
        title: "Yakin ingin menghapus halaman?",
        text: "Halaman yang telah dihapus tidak akan bisa diakses lagi",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, hapus saja!",
        cancelButtonText: "Batalkan",
      }).then((result) => {
        if (result.value) {
          this.dispatchDeletePage(id);
          if (otherOne) {
            this.dispatchDeletePage(otherOne);
          }
        }
      });
    },
    saveMenu() {
      this.$store.dispatch("saveMenu");
      // .then(()=>{
      //   this.activeMenu = this.menus.length
      // })
    },
    setHomepage(id) {
      if (
        id == this.$store.state.project.post_index ||
        id == this.$store.state.project.post_single
      ) {
        return this.$swal({
          title: "Perhatian!",
          text: "Halaman ini sudah dijadikan Daftar/Detail artikel",
          type: "warning",
        });
      }
      this.loading = true;
      this.$store
        .dispatch("setHomepage", id)
        .then(() => {
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
    setPostIndex(id) {
      if (id == this.$store.state.project.homepage) {
        return this.$swal({
          title: "Perhatian!",
          text: "Halaman ini sudah dijadikan Beranda",
          type: "warning",
        });
      }
      this.loading = true;
      this.$store
        .dispatch("setPostIndex", id)
        .then(() => {
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
    setPostSingle(id) {
      if (id == this.$store.state.project.homepage) {
        return this.$swal({
          title: "Perhatian!",
          text: "Halaman ini sudah dijadikan Beranda",
          type: "warning",
        });
      }
      this.loading = true;
      this.$store
        .dispatch("setPostSingle", id)
        .then(() => {
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
    setCatalogIndex(id) {
      if (id == this.$store.state.project.homepage) {
        return this.$swal({
          title: "Perhatian!",
          text: "Halaman ini sudah dijadikan Beranda",
          type: "warning",
        });
      }
      this.loading = true;
      this.$store
        .dispatch("setCatalogIndex", id)
        .then(() => {
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
    setCatalogSingle(id) {
      if (id == this.$store.state.project.homepage) {
        return this.$swal({
          title: "Perhatian!",
          text: "Halaman ini sudah dijadikan Beranda",
          type: "warning",
        });
      }
      if (id == this.$store.state.project.post_single) {
        return this.$swal({
          title: "Perhatian!",
          text: "Halaman ini sudah dijadikan detail posting",
          type: "warning",
        });
      }
      this.loading = true;
      this.$store
        .dispatch("setCatalogSingle", id)
        .then(() => {
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
    setAsLandingPage(id) {
      this.loading = true;
      this.$store.dispatch("setLandingTemplate", id).then(() => {
        this.$notify({
          group: "builder",
          title: "Landing Page saved",
          text: `Successfully saving this page as landing page`,
          type: "success",
        });
        this.loading = false;
      });
    },
    addLink() {
      const link = {
        type: "link",
        label: "Link Baru",
        url: "https://example.com",
        menuId: randomId(6, "aA0"),
        new_tab: true,
        hidden: false,
        single: false,
      };
      // this.activeMenu = this.menus.length
      this.$store.commit("addMenu", link);
      this.$store.dispatch("saveMenu");
    },
    addDropdownMenu() {
      if (!this.disabledDropdownMenu) {
        return this.$swal.fire({
          title: "Halaman Produk belum ada",
          text: "Untuk membuat drop down menu, Halaman Produk harus ada",
          type: "info",
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Ok",
        });
      }

      const link = {
        type: "product",
        label: "Dropdown Menu",
        menuId: randomId(6, "aA0"),
        hidden: false,
        homepage: false,
        single: false,
        category_id: 0,
        color: "white",
        backgroundColor: "black",
      };
      
      this.$store.commit("addMenu", link);
      this.$store.dispatch("saveMenu");
    },
    setActiveMenu(id) {
      this.$store.commit("selectedMenu", id);
    },
    removeLink(id) {
      this.$swal({
        title: "Yakin ingin menghapus  Link?",
        text: "Halaman yang telah dihapus tidak akan bisa diakses lagi",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, hapus saja!",
        cancelButtonText: "Batalkan",
      }).then((result) => {
        if (result.value) {
          this.$store.commit("removeMenu", id);
        }
      });
    },
    menuClick(menu) {
      if (menu.type === "page") {
        this.$store.commit("ACTIVE_BLOCK", null);
        this.$store.commit("editing", menu.id);
      }

      return false;
    },
    saveAsTemplate(id) {
      const index = findIndex(this.project.pages, (i) => i.id == id);
      const page = this.project.pages[index];
      if (!page.blocks.length)
        this.$swal({
          type: "warning",
          title: "Oops...",
          text:
            "You can not save empty page as template, start adding blocks now",
        });

      return this.$swal({
        title: "Save page as template",
        input: "text",
        inputAttributes: {
          autocapitalize: "off",
        },
        showCancelButton: true,
        confirmButtonText: "Save",
      }).then((result) => {
        if (result.value) {
          const data = {
            title: result.value,
            blocks: JSON.stringify(cloneDeep(page.blocks)),
          };

          const options = {
            method: "POST",
            headers: { "content-type": "application/x-www-form-urlencoded" },
            data: qs.stringify(data),
            url: this.$root.config.api + "/templates/user",
            withCredentials: true,
          };

          Axios(options)
            .then(() => {
              this.$notify({
                group: "builder",
                title: "Template saved",
                text: `Your Template is saved, now you can use it across your project`,
                type: "success",
              });
              this.$store.dispatch("getUserTemplates");
            })
            .catch((error) => {
              this.$notify({
                group: "builder",
                title: "Error saving page",
                text: `We have encountered error when saving page, reason: ${error}`,
                type: "error",
              });
            });

          // this.$swal.fire({
          //   title: `${result.value.login}'s avatar`,
          //   imageUrl: result.value.avatar_url
          // })
        }
      });
    },
    openLinkBuilder(url) {
      this.$linkBuilder.setDefaultData(url);
      this.$linkBuilder.show({
        onSelected: (value) => {
          const index = findIndex(
            this.menus,
            (i) => i.menuId == this.editorState.selectedMenu
          );

          this.menus[index].url = value;

          this.$store.commit("dirty", true);
        },
      });
    },
    getPage(pageId) {
      return this.project.pages.filter(val=>parseInt(val.id)===parseInt(pageId))[0];
    },
    openPagePersonalization(menuId) {
      if (menuId) {
        this.setActiveMenu(menuId);
        this.$store.commit("openPagePersonalization", true);
      }
      else {
        this.setActiveMenu(null);
        this.$store.commit("openPagePersonalization", false); 
      }
    },
    setActivePersonalizationTab(tab) {
      this.$store.commit('activePersonalizationTab', tab);
    },
    checkPersonalizationUserTag(label, selectedOpt) {
      let pagePersonalization = cloneDeep(this.getPage(this.menuOptions.id)).personalization;
      if (typeof pagePersonalization === 'undefined') {
        pagePersonalization = {user_tag:[], rules:[]};
        this.$store.commit('updatePagePersonalization', {id: this.menuOptions.id, personalization: pagePersonalization});
      }
      let setting = pagePersonalization.user_tag.filter(val => val.label.toLowerCase().trim() === label.toLowerCase().trim() ); // get setting like [{"label":"age","options":[]}] 
      let checked = false;
      setting.forEach(val => {
        if ( val.label.toLowerCase().trim() === label.toLowerCase().trim() ) {
          if ( findIndex(val.options, option => option == selectedOpt) !== -1 ) {
            checked = true;
          }
        }
      })
      return checked;
    },
    changePersonalizationUserTag(event, label, selectedOpt) {
      let pagePersonalization = cloneDeep(this.getPage(this.menuOptions.id)).personalization;
      let personalizationUserTag = pagePersonalization.user_tag;
      let setting = personalizationUserTag.filter(val => val.label.toLowerCase().trim() === label.toLowerCase().trim() ); // get setting like [{"label":"age","options":[]}] 
      let checked = event.target.checked;

      if (setting.length === 0) {
        // add setting
        personalizationUserTag.push({
          "label" : label,
          "options" : checked ? [selectedOpt] : []
        });
      }
      else {
        // modify setting
        personalizationUserTag = personalizationUserTag.map(val => {
          if ( val.label.toLowerCase().trim() === label.toLowerCase().trim() ) {
            let idx = findIndex(val.options, option => option == selectedOpt);
            if (checked) {
              if (idx === -1) { //not exist
                // add to list
                val.options.push(selectedOpt);
              }
            }
            else{
              if (idx !== -1 ) { //exist
                // remove from list
                val.options = [...val.options.slice(0,idx), ...val.options.slice(idx+1)];
              }
            }
          }
          return val;
        });
      }

      pagePersonalization = {user_tag: [...personalizationUserTag], ...pagePersonalization};

      this.$store.commit('updatePagePersonalization', {id: this.menuOptions.id, personalization: pagePersonalization});
      this.$store.commit('dirty', true);
    }
  },
};
</script>

<style lang="less">
.icl-editor__tab.icl-editor__page {
  padding: 0;
}
.icl-editor__page-content {
  display: flex;
  flex-direction: column;
  position: absolute;
  width: 100%;
  top: 0;
  bottom: 0;
  overflow: hidden;

  .page-list,
  .page-options {
    position: absolute;
    top: 0;
    bottom: 0;
    width: 100%;
    left: 0;
    overflow: auto;
  }
}
.page-list {
  .site-menu {
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    overflow-x: hidden;
  }
  .page-item {
    background-color: white;
    // border: 1px solid #e3e3e3;
    // margin-bottom: 5px;
    transition: 0.3s ease;

    &.is-active .page-item__header {
      background-color: #f0f0f0;
      // color: white;

      button {
        color: inherit;
      }
    }
    &:hover .page-item__header {
      background-color: #f5f5f5;

      button {
        opacity: 1;
      }
    }
    &.is-hidden {
      .page-item__header {
        opacity: 0.6;
      }
    }
    &__header {
      padding: 0 0 0 15px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      cursor: pointer;
      transition: 0.3s ease;
      .menu-icon {
        i {
          line-height: 1;
          font-size: 20px;
          margin-right: 10px;
          opacity: 0.7;
        }
      }
      .menu-title {
        font-size: 14px;
        display: flex;
        flex: 1;
        align-items: center;
        font-weight: 400;
      }
      button {
        border: none;
        background-color: transparent;
        line-height: 1;
        color: #666;
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        padding: 15px 10px;
        text-align: center;
        opacity: 0;
        transition: 0.3s ease;

        i {
          font-size: 20px;
          line-height: 1;
        }
      }
    }
    &__content {
      border-top: 1px solid #e3e3e3;
      padding: 15px;

      .form-group:last-child {
        margin-bottom: 0;
      }
      .input-group-text,
      .form-control {
        font-size: inherit;
      }

      > .btn-group {
        margin: 20px -15px -15px;
        display: flex;

        .btn {
          border-radius: 0;
          display: flex;
          justify-content: center;
          align-items: center;
          flex: 1;
          i {
            margin-right: 10px;
            font-size: 1em;
          }
        }
      }
    }
  }
}
.pages-action {
  display: flex;
  margin-bottom: 15px;
  .btn {
    display: flex;
    align-items: center;
    text-align: center;
    justify-content: center;
    flex: 1;

    i {
      margin-right: 10px;
    }
  }
}
.page-quota {
  background-color: white;
  padding: 15px 20px;
  margin: 0 -20px 30px;
  border-top: 1px solid #e3e3e3;
  border-bottom: 1px solid #e3e3e3;

  &-progress {
    border-radius: 3px;
    overflow: hidden;
    position: relative;
    height: 5px;
    background-color: rgba(0, 0, 0, 0.1);

    &-bar {
      background-color: var(--primary);
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
    }
  }
}
.page-options {
  position: absolute;
  top: 0;
  bottom: 0;
  width: 100%;
  left: 0;
  transform: translateX(0);
  transition: 0.3s ease-out;
  background-color: white;
  display: flex;
  flex-direction: column;

  &-header {
    padding: 20px;
    button {
      background-color: transparent;
      border: none;
      padding: 0;
    }
  }
  &-fields {
    flex: 1;
    overflow: auto;
    padding: 20px;
  }
  .form-label {
    font-weight: 700;
  }
  .set-homepage {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    i {
      margin-right: 10px;
    }
  }
  &-actions {
    padding: 15px 20px;
    border-top: 1px solid #e3e3e3;
    background-color: white;
    .btn {
      width: 100%;
      justify-content: center;
      margin: 10px 0;
      i {
        margin-right: 10px;
        vertical-align: middle;
      }
    }
  }
}

.page-type {
  label {
    font-weight: 700;
  }
  &-options {
    // display: flex;
    box-shadow: 0 0 0 1px #aaa;
    border-radius: 4px;

    .btn {
      flex: 1;
      border-radius: 0;
      border-right-width: 0;
      // flex-direction: column;
      border: none;
      text-align: left;
      width: 100%;

      .mdi {
        height: 1em;
        // margin-bottom: 5px;
        margin-right: 10px;
        font-size: 26px;
      }

      &:first-child {
        border-top-left-radius: 3px;
        border-bottom-left-radius: 3px;
      }

      &:last-child {
        border-top-right-radius: 3px;
        border-bottom-right-radius: 3px;
      }

      &:hover {
        background-color: #e3e3e3;
        color: currentColor;
      }

      &.is-active {
        background-color: var(--primary);
        color: white;
      }
    }
  }
}

.page-option-domain{
  display: flex;
  > div{
    padding: 15px 10px;
    flex: 1;
    display: flex;
    // flex-direction: column;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    background-color: #e7e9eb;

    &.is-active{
      background-color: white;
    }
  }
  i{
    margin-right: 5px;
  }
}
</style>
