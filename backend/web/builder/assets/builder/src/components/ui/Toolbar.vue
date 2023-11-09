<template>
  <div class="icl-editor__toolbar">
    <div class="icl-editor__toolbar-left">
      <a
        v-if="this.$root.config.back_url"
        class="icl-editor__close"
        :href="this.$root.config.back_url"
        @click.prevent="back_to_dashboard"
      >
        <i class="mdi mdi-arrow_back" />
      </a>
      <div class="editor-title">
        <h2>{{ project.name }}</h2>
        <div>
          <small
            >
            <a :href="project.url" target="_blank" rel="noopener noreferrer">{{ project.url }}</a>
          </small>
        </div>
      </div>
    </div>

    <div class="icl-editor__toolbar-center">
      <div class="icl-editor__responsive">
        <button
          :class="{ 'is-active': editorState.previewSize == 'desktop' }"
          @click="setPreviewSize('desktop')"
        >
          <i class="mdi mdi-desktop_mac" />
        </button>
        <button
          :class="{ 'is-active': editorState.previewSize == 'tablet' }"
          @click="setPreviewSize('tablet')"
        >
          <i class="mdi mdi-tablet_mac" />
        </button>
        <button
          :class="{ 'is-active': editorState.previewSize == 'mobile' }"
          @click="setPreviewSize('mobile')"
        >
          <i class="mdi mdi-phone_iphone" />
        </button>
      </div>
    </div>

    <div class="icl-editor__toolbar-right">
      <a
        v-if="project.url"
        class="btn btn-normal btn-visit"
        target="_blank"
        :href="project.url + '/' + pageSlug + detailSlug"
      >
        <i class="mdi mdi-explore" /> Kunjungi halaman
      </a>

      <div class="icl-editor__actions d-flex">
        <button v-if="editing" class="btn btn-preview" @click="previewPage">
          <i class="mdi mdi-search" /> Pratinjau
        </button>
        <button
          class="btn btn-save"
          :class="{ loading: editorState.saving }"
          :disabled="!editorState.dirty || editorState.saving"
          @click="saveProject"
        >
          {{ editorState.saving ? "Menyimpan" : "Simpan" }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import Axios from "axios";
import qs from "qs";
import findIndex from "lodash/findIndex";
import cloneDeep from "lodash/cloneDeep";
import map from "lodash/map";
import EventBus from "@/components/EventBus";

export default {
  name: "Toolbar",
  computed: {
    editorState() {
      return this.$store.state.editorState;
    },
    editing() {
      return this.$store.state.editing;
    },
    currentlyEditingTitle() {
      const index = findIndex(
        this.$store.state.project.pages,
        (i) => i.id == this.editing
      );
      if (index !== -1) return this.$store.state.project.pages[index].title;

      return "";
    },
    project() {
      return this.$store.state.project;
    },
    pageSlug() {
      let slug = "";
      const pageId = this.editing; // the page user is currently viewing

      if (pageId) {
        // find id in pages array
        const pageObj = this.project.pages.find(function(page) {
          return page.id === pageId;
        });

        // find 1 block whose blockType includes 'detail' text
        const detailBlock = pageObj.blocks.find(function(block) {
          return block.blockType && block.blockType.includes("detail");
        });

        // find 1 page that has a block that includes 'index' text
        const indexBlock =
          detailBlock &&
          this.$store.state.project.pages.find(function(page) {
            return (
              page.blocks.length > 0 &&
              page.blocks.find(function(block) {
                return (
                  block.blockType &&
                  block.blockType.includes("index") &&
                  block.category &&
                  block.category === detailBlock.category
                );
              })
            );
          });

        if (detailBlock && indexBlock) {
          slug = indexBlock.slug + "/";
        } else {
          // const menuSlug = this.project.menus.find(function(menu) {
          //     return menu.id === pageId;
          //   }).slug;
            
          // if (menuSlug === "home") {
          //   return slug;
          // }
          console.log(this.project.menus);
          const menu = this.project.menus.find(function(menu) {
            return menu.id === pageId;
          });

          if (menu.homepage) {
            return slug;
          }

          slug = menu.slug + "/";
        }
      }

      return slug;
    },
    detailSlug() {
      let slug = "";

      if (this.editing) {
        // find id in pages array
        const pageObj = this.project.pages.find(
          (page) => page.id === this.editing
        );

        // find 1 block whose blockType includes 'detail' text
        const detailBlock = pageObj.blocks.find(
          (block) => block.blockType && block.blockType.includes("detail")
        );

        if (pageObj.blocks.length > 0 && detailBlock) {
          // if blockType includes 'detail' text
          let latestCatalogOrProduct = "";

          if (detailBlock.category === "catalog") {
            latestCatalogOrProduct = this.$store.state.project.catalogs[0];
          } else if (detailBlock.category === "post") {
            latestCatalogOrProduct = this.$store.state.project.posts[0];
          }

          if (latestCatalogOrProduct) {
            // if there is a product or catalog
            slug = latestCatalogOrProduct.slug;
          }
        }
      }

      return slug;
    },
  },
  data() {
    return {
      preview: null,
    };
  },
  methods: {
    back_to_dashboard(event) {
      const target = event.target.closest(".icl-editor__close").href;
      if (this.editorState.dirty)
        return this.$swal({
          title: "Projek belum disimpan",
          text: "Anda memiliki perubahan yang belum disimpan",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#3085d6",
          confirmButtonText: "Keluar Editor",
          cancelButtonText: "Batal",
          focusConfirm: false,
          focusCancel: true,
        }).then((result) => {
          if (result.value) {
            return (window.location.href = target);
          }
        });

      return (window.location.href = target);
    },
    setPreviewSize(size) {
      this.$store.dispatch("setPreviewSize", size);
      EventBus.$emit("previewSize");
    },
    previewPage() {
      this.$store.commit(
        "previewMode",
        !this.$store.state.editorState.previewMode
      );
      EventBus.$emit("previewMode");
    },
    async saveProject() {
      let dataContent = '';

      let config_api = this.$root.config.api; // output : baseurl/api/v1/project
      let baseurl = config_api.split('/api/v1')[0]; 

      await Axios.get(`${baseurl}/session/token`)
        .then( response => {
          dataContent = response.data;
        } )

      const project = cloneDeep(this.$store.state.project);

      project.pages.map((page) => {
        page.blocks = this.prepareForSaving(page.blocks);

        return page;
      });

      const options = {
        method: "POST",
        headers: {"X-CSRF-Token": dataContent, "cookie" : "", "content-type": "application/json" },
        data: project,
        url: this.$root.config.api,
        withCredentials: true,
      };

      this.$store.commit("saving", true);
      this.$nprogress.start();
      return Axios(options)
        .then(() => {
          this.$nprogress.done();
          this.$store.commit("saving", false);
          this.$notify({
            group: "builder",
            title: "Projek tersimpan",
            text: `Penyimpanan projek berhasil`,
            type: "success",
          });
          this.$store.commit("dirty", false);
        })
        .catch(() => {
          this.$store.commit("saving", false);
          this.$notify({
            group: "builder",
            title: "Penyimpanan Gagal",
            text: `Proses penyimpanan projek gagal`,
            type: "error",
          });
        });
    },
    /*
      saveProject(){
        const ids = this.$store.state.project.pages.map(page=>page.id)
        
        ids.map( id=>{
          this.savePage(id)
        } )
        this.$store.dispatch('saveMenu');
        this.$store.dispatch('saveStyle');
        this.$store.dispatch('saveMeta');
        
        this.$notify({
          group : 'builder',
          title : 'Project saved',
          text  : `All project successfully saved`,
          type  : 'success'
        });
      },
      */
    savePage(id) {
      const pageIndex = findIndex(
        this.$store.state.project.pages,
        (i) => i.id == id
      );

      const page = cloneDeep(this.$store.state.project.pages[pageIndex]);
      // var formData  = new FormData();

      // return console.log( page );

      this.$store.commit("saving", true);

      const data = {
        id: page.id,
        title: page.title,
        description: page.description,
        slug: page.slug,
        label: page.label,
        homepage: this.$store.state.project.homepage == page.id,
        blocks: JSON.stringify(this.prepareForSaving(page.blocks)),
        ...this.$root.config.extra,
      };

      const options = {
        method: "POST",
        headers: { "content-type": "application/x-www-form-urlencoded" },
        data: qs.stringify(data),
        url: this.$root.config.api + "/save",
        withCredentials: true,
      };

      return Axios(options)
        .then(() => {
          this.$store.commit("saving", false);

          this.$store.commit("dirty", false);
        })
        .catch(() => {
          this.$store.commit("saving", false);
        });
    },
    prepareForSaving(blocks) {
      return blocks.map((block) => {
        map(block.data, (setting) => {
          delete setting.label;
          delete setting.horizontal;

          if (setting.type == "repeatable") {
            delete setting.item_title;
            delete setting.settings;

            map(setting.value, (items) => {
              map(items, (item) => {
                delete item.label;
                delete item.horizontal;

                if (setting.type == "slider") {
                  delete setting.min;
                  delete setting.max;
                }

                if (
                  item.type == "radio-icon" ||
                  item.type == "select" ||
                  item.type == "radio-image"
                ) {
                  delete item.options;
                }

                delete item.type;

                return item;
              });

              return items;
            });
          }

          if (setting.type == "slider") {
            delete setting.min;
            delete setting.min;
          }

          if (
            setting.type == "radio-icon" ||
            setting.type == "select" ||
            setting.type == "radio-image"
          ) {
            delete setting.options;
          }

          delete setting.type;

          return setting;
        });

        map(block.style, (style) => {
          delete style.type;
          delete style.horizontal;
          delete style.label;

          return style;
        });

        return block;
      });
    },
  },
};
</script>

<style lang="less">
.icl-editor {
  &__toolbar {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    position: fixed;
    width: 100%;
    z-index: 3;
    top: 0;
    left: 0;
    align-items: center;
    transition: 0.3s ease;
    height: 70px;
    box-sizing: border-box;

    .preview-mode & {
      transform: translateY(-100%);
    }

    &-left,
    &-center,
    &-right {
      display: flex;
    }
    &-center {
      flex-shrink: 0;
    }
    &-left,
    &-right {
      width: 100%;
    }
    &-left {
      justify-content: flex-start;
    }
    &-right {
      justify-content: flex-end;
    }

    .icl-editor__close {
      color: inherit;
      display: flex;
      padding: 0 10px;
      align-items: center;
      margin-right: 15px;
      text-decoration: none;

      &:hover {
        text-decoration: none;
      }

      .material-icons {
        font-size: 32px;
      }
    }

    .btn {
      display: flex;
      align-items: center;
      font-weight: 700;
      &-visit {
        color: #444;
        i {
          color: var(--primary);
          margin-right: 5px;
        }
      }
    }

    .btn-save {
      &[disabled] {
        opacity: 0.5;
        cursor: not-allowed;
      }
      &:before {
        content: "\e161";
        font-family: "Material Icons";
        font-weight: normal;
        font-style: normal;
        font-size: 24px;
        line-height: 1;
        letter-spacing: normal;
        text-transform: none;
        display: inline-block;
        white-space: nowrap;
        word-wrap: normal;
        direction: ltr;
        -webkit-font-feature-settings: "liga";
        -webkit-font-smoothing: antialiased;
        margin-right: 5px;
        position: relative;
      }

      &.loading:before {
        content: "\e2c3";
      }
    }
  }
  &__actions {
    button {
      background-color: var(--primary);
      color: white;
      border: none;
      display: inline-flex;
      align-items: center;
      padding: 5px 15px;
      border-radius: 5px;
      margin-left: 5px;
      text-transform: uppercase;
      font-weight: 900;
      font-size: 13px;
      i {
        margin-right: 10px;
      }
      &:hover {
        color: white !important;
      }
    }
    .btn-preview {
      background-color: var(--green);
    }
  }
  &__responsive {
    border-radius: 4px;
    overflow: hidden;
    box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.1);

    button {
      padding: 8px 10px;
      line-height: 1;
      border: none;
      background-color: white;
      box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.1);
      display: inline-flex;
      outline: none;

      &.is-active {
        background-color: var(--primary);
        color: white;
      }
    }
  }
}
.editor-title {
  small {
    font-style: italic;
  }
  h2 {
    font-size: 20px;
    line-height: 1;
    max-width: 420px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    margin: 0;
    text-transform: capitalize;
  }
}
</style>
