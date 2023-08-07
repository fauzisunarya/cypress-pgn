<template>
<div class= "icl-editor__theme icl-editor__tab">
  <div class="icl-editor__page-content">
    <div class="page-list">
      <div class="mb-4 pt-3 px-3">
        <h3 class="panel-title"><i class="mdi mdi-brush"></i> Tema</h3>
        <p>Ubah warna, jenis dan ukuran teks untuk halaman anda disini</p>
      </div>
      
      <div class="theme-section" v-for="section in theme">
        <h2 class="theme-section-title">{{section.label}}</h2>
        <div class="block-option__field" v-for="field in section.settings">
          <component :is="`field-${field.type}`" :data="field" />
        </div>
      </div>

    </div>
  </div>

</div>
</template>

<script>
import qs from 'qs'
import Axios from 'axios'
import Color from '../fields/Color'
import Select from '../fields/Select'
import Typography from '../fields/Typography'
import Slider from '../fields/Slider'
export default {
  name : "Theme",
  components: {
    "field-color": Color,
    "field-select": Select,
    "field-typography": Typography,
    "field-slider": Slider
  },
  computed: {
    theme(){
      return this.$store.state.project.style
    }
  },
  watch: {
    theme: {
      handler(after, before){
        this.$store.commit('updateStyle', after)
      },
      deep: true
    }
  },

  methods: {
    
  }
}
</script>

<style lang="less">
.icl-editor__tab.icl-editor__theme{
  padding: 0;
  &-content{
    display: flex;
    flex-direction: column;
    position: absolute;
    width: 100%;
    top: 0;
    bottom: 0;

  }
}
.theme-section-title{
  font-size: 18px;
  margin-bottom: 15px;
  margin: 30px 0 15px;
  padding: 10px 20px;
  background-color: white;
}
.icl-editor{
  .font-picker{
    width: 100%;
    box-shadow: none;

    .dropdown-button{
      background: white;
      border: 1px solid #e3e3e3;
      border-radius: 3px;
      padding: 10px 15px;
      height: auto;

      &:hover, &.expanded{
        background: #fbfbfb;
      }
    }
    .expanded{
      background: #fbfbfb;
      button{
        font-size: 20px;
      }
    }
  }
}
</style>