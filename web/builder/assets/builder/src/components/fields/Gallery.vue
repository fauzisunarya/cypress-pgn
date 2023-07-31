<template>
<div class="icl-ed-field icl-ed-field--gallery">
  <div class="icl-ed-field icl-ed-field--horizontal">
    <label class="form-label">{{data.label}}</label>
    <button v-if="data.value.length" @click="showMediaPicker" class="btn btn-primary select-gallery"><i class="mdi mdi-collections mr-2"></i>Medias</button>
  </div>

  <div class="selected-gallery">
    <div v-if="!data.value.length" class="text-center p-4" style="width:100%">
      <p>No image selected</p>
      <button @click="showMediaPicker" class="btn btn-primary select-gallery d-inline-flex"><i class="mdi mdi-collections mr-2"></i>Medias</button>
    </div>
    <div v-else class="gallery-item" v-for="(image, index) in data.value">
      <img :src="image" :alt="image">
      <button @click="removeItem(index)"><i class="mdi mdi-delete"></i></button>
    </div>
  </div>
</div>
</template>

<script>
import Axios from 'axios'
export default {
  name: 'field-gallery',
  props: {
    data: Object
  },
  watch: {
    'data.value' : {
      handler(after, before){
        if( after !== before )
          this.$store.commit('dirty', true)
      },
      deep: true
    }
  },
  methods: {
    showMediaPicker(){
      this.$mediaPicker.show({
        title    : "Select images",
        selected : this.data.value,
        multiple : true,
        onSelected: (value) =>{
          this.data.value = value;
          this.$store.commit('dirty', true);
        }
      })
    },
    removeItem(index) {
       this.$swal({
        title: 'Yakin ingin menghapus item?',
        text: "Item yang telah dihapus tidak akan bisa diakses lagi",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus saja!',
        cancelButtonText: 'Batalkan'
      }).then((result) => {
        if (result.value) {
          this.data.value.splice(index,1);
          this.$store.commit('dirty', true);
        }
      });
    }
  }
}
</script>

<style lang="less">
  .media-picker{
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    margin-top: 10px;
    &-option{
      display: flex;
    }
    &-upload{
      flex: 1;
      margin: 0!important;
      &:active{
        background-color: var(--primary);
        color: white;
      }
      input{
        display: none;
      }
      div{
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px 15px;
      }
    }
    &-library{
      flex: 1;
      background-color: transparent;
      border: none;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 10px 15px;

      &.is-active{
        background-color: var(--primary);
        color: white;
      }
    }
    .material-icons {
      margin-right: 15px;
    }

    &-grid{
      border-top: 1px solid #e3e3e3;
      display: flex;
      flex-wrap: wrap;
      padding: 5px;
      max-height: 358px;
      overflow: auto;

      div{
        width: 33.333%;
        padding: 5px;

        img{
          width: 100%;
        }
      }
    }
  }
  .icl-ed-field--gallery{
    .select-gallery{
      display: flex;
      align-items: center;

      i{
        font-size: 20px
      }
    }
  }
  .selected-gallery{
    display: flex;
    flex-wrap: wrap;
    background-color: #fbfbfb;
    border: 1px solid #e3e3e3;
    padding: 5px;
    margin-top: 15px;

    .gallery-item {
      width: 33.33%;
      height: 33.33%;
      padding: 5px;
      position: relative;
      img{
        max-width: 100%;
        width: 100%;
        height: 100px;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 1px 1px rgba(0,0,0,.1)
      }

      button{
        width: 30px;
        height: 30px;
        background-color: darken(red, 5%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 50%;
        position: absolute;
        top: 5px;
        right: 5px;
        border: 2px solid white;
        i {font-size: 16px;}
      }
    }
  }
</style>