<template>
  <div class="icl-ed-field">
    <label v-if="data.label" class="form-label">{{data.label}}</label>
    <div class="field-repeatable">
      <draggable
        class="repeatable-field"
        v-if="data.pagePersonalizationRules.length"
        :list="data.pagePersonalizationRules"
        group="structure"
        handle=".repeatable-field-item__handle"
        v-bind="dragOptions"
        @start="drag = true"
        @end="endDragging"
        >
        <!-- <transition-group type="transition" :name="!drag ? 'flip-list': null"> -->
          <div class="repeatable-field-item" v-for="(item, index) in data.pagePersonalizationRules" :key="`key-${index}`">
            <div class="repeatable-field-item__header" @click="toggleActiveItem(index)">
              <div class="repeatable-field-item__handle"><i class="mdi mdi-drag_handle"></i></div>
              <span class="repeatable-field-item__title" v-if="data.item_title">{{stripTags(item[data.item_title].value)}}</span>
              <span class="repeatable-field-item__title" v-else>Rules {{index+1}}</span>
              <button class="repeatable-field-item__delete" @click.prevent="removeRepeatableItem(index)" data-tooltip="Hapus Item"><i class="mdi mdi-delete"></i></button>
            </div>
            <div class="repeatable-field-item__content" v-if="activeItem == index">
              <div class="form-group">
                <component :is="`field-wyswyg`" :data="item.title" :halo="item.redirect"></component>
              </div>
              <div class="form-group" v-for="(item, key) in data.personalization" :key="key">
                <div class="pt-2 py-1 px-0">
                  <h6>{{item.label}}</h6>
                </div>
                <div class="icl-ed-field icl-ed-field--horizontal" v-for="(option, index) in item.options" :key="index">
                  <label class="form-label" style="font-weight:500">{{option}}</label>
                  <div class="field-switch">
                    <label>
                      <input type="checkbox" :checked="checkPersonalizationUserTag(item.label, option)" @change="changePersonalizationUserTag($event, item.label, option)">
                      <span class="field-switch-ui"></span>
                    </label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="icl-ed-field icl-ed-field--horizontal">
                  <label class="form-label">Redirect</label>
                  <div class="field-select">
                    <div>
                      <select class="form-control" v-model="item.redirect">
                        <option v-for="(page, key) in menuOptions" :value="page.id" :key="key">Page {{page.label}}</option>
                      </select>  
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <!-- </transition-group> -->
      </draggable>
      
      <button
        class="btn btn-outline-secondary repeatable-field-item__add"
        @click="addRepeatableItem"
      >
        <i class="mdi mdi-add"></i>
        Tambah Rules
      </button>
    </div>
  </div>
</template>

<script>
import draggable from 'vuedraggable'

import cloneDeep from 'lodash/cloneDeep'
import findIndex from "lodash/findIndex";
import WYSWYG from "@/components/fields/wyswyg"

export default {
  name: 'field-personalization-rules',
  components: {
    draggable,
    'field-wyswyg': WYSWYG,
  },
  props: {
    data: Object,
  },
  data(){
    return {
      drag: false,
      itemTemplate: {
        title: {
          type : "wyswyg",
          label : "Judul",
          heading : true,
          horizontal : true,
          value : 'Rules'
        },
        user_tag: [],
        redirect: this.data.page.id //default to this page
      },
      activeItem: null
    }
  },
  mounted(){
    // 
  },
  computed: {
    dragOptions() {
      return {
        animation: 200,
        group: "structure",
        disabled: false,
        ghostClass: "ghost",
        fallbackAxis: 'y',
        forceFallback: true
      };
    },
    menuOptions(){
      return this.$store.state.project.menus.filter(menu=>menu.type==='page')
    }
  },
  watch: {
    'data.pagePersonalizationRules': {
      handler(after, before){
        if( after !== before )
          // console.log("updated");
          this.$store.commit('dirty', true)
      },
      deep: true
    }
  },
  methods: {
    stripTags( value ) {
      return value.replace(/(<([^>]+)>)/ig,"");
    },
    toggleActiveItem(index){
      if (this.activeItem == index){
        this.activeItem = null
      } else {
        this.activeItem = index
      }
    },
    addRepeatableItem(){
      const limit = this.data.limit || 99999;
      if ( this.data.pagePersonalizationRules.length == this.data.limit )
        return this.$swal({
            title: 'Item terbatas',
            text: `Anda hanya bisa menambahkan ${this.data.limit} item untuk blok ini`,
            type: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Ok',
          })
      this.data.pagePersonalizationRules.push(cloneDeep(this.itemTemplate));
      this.activeItem = this.data.pagePersonalizationRules.length - 1;
      this.$store.commit('dirty', true);
    },
    removeRepeatableItem(index){
      this.$swal({
        title: 'Hapus item ini?',
        text: "Anda tidak dapat mengembalikkannya lagi.",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus saja!'
      }).then((result) => {
        // console.log(result);
        if (result.value) {
          this.data.pagePersonalizationRules.splice(index, 1);
          this.$store.commit('dirty', true);
        }
      });
    },
    endDragging(){
      this.drag = false;
      this.$store.commit('dirty', true);
    },
    checkPersonalizationUserTag(label, selectedOpt) {
      let rules = this.data.pagePersonalizationRules[this.activeItem];
      let user_tag = rules.user_tag;
      let setting = user_tag.filter(val => val.label.toLowerCase().trim() === label.toLowerCase().trim() ); // get setting like [{"label":"age","options":[]}] 
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
      let rules = this.data.pagePersonalizationRules[this.activeItem];
      let user_tag = rules.user_tag;
      let setting = user_tag.filter(val => val.label.toLowerCase().trim() === label.toLowerCase().trim() ); // get setting like [{"label":"age","options":[]}] 
      let checked = event.target.checked;

      if (setting.length === 0) {
        // add setting
        user_tag.push({
          "label" : label,
          "options" : checked ? [selectedOpt] : []
        });
      }
      else {
        // modify setting
        user_tag = user_tag.map(val => {
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

      this.$store.commit('dirty', true);
      // already change the global state (vuex), because this props was changed (syncron)
    }
  }
}
</script>

<style lang="less">
.repeatable-field{
  box-shadow: 0 0 1px 1px rgba(0,0,0,.1);
}
.repeatable-field-item{
  border: 1px solid #aaa;

  &__header{
    border-bottom: 1px solid #aaa;
    display: flex;
    align-items: center;

    span{
      flex: 1;
      text-align: left;
      // text-transform: uppercase;
      font-weight: 700;
      padding-left: 15px;
      padding-right: 15px;
    }
  }
  &__content{
    background-color: lighten(#e9ecf1,5%);

    .form-group{
      margin-bottom: 0;
      padding: 10px 0;
      // &:not(:last-child){
      //   border-bottom: 1px solid rgba(0,0,0,.1);
      // }
      &:first-child{ padding-top: 0 }
      &:last-child{ padding-bottom: 0 }
    }

    .field-button{
      padding: 0;
      border: none;
    }
  }

  &:last-child{
    border-bottom-color: #aaa
  }
}
.repeatable-field-item__handle{
  cursor: move;
  border-right: 1px solid #e3e3e3!important;
}
.repeatable-field-item__title{
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.repeatable-field{
  margin-bottom: 15px;

  &-item{
    border: 1px solid #e3e3e3;
    border-bottom: none;
    background-color: white;

    &:last-child{
      border-bottom: 1px solid #e3e3e3;
    }

    &__header{
      display: flex;
      justify-content: space-between;
      border-bottom: 1px solid #e3e3e3;
      cursor: pointer;
    }
    &__handle,
    &__delete{
      padding: 10px;
      display: flex;
      align-items: center;
      border: none;
    }
    &__delete{
      color: darken(red, 10%);
      opacity: 0;
      transition: .3s ease;
      .material-icons{font-size: 18px;}
    }
    &:hover .repeatable-field-item__delete{
      opacity: 1;
    }
    &__add{
      width: 100%;
      padding: 10px 15px;
      border: 1px solid #e3e3e3;
      text-align: center;
      justify-content: center;
    }
    &__content{
      padding: 15px;
    }
  }
}

</style>