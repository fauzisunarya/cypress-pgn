<template>
<div class="icl-editor__sidebar" ref="sidebar">
  <div class="icl-editor__sidebar-tabs">
    <div class="editor-tab editor-tab-page" :class="{'is-active': editorState.activeSidebarTab== 'page' }" @click="setSidebarTab('page')"><i class="mdi mdi-description"></i><span>Halaman</span></div>
    <div class="editor-tab editor-tab-structure" :class="{'is-active': editorState.activeSidebarTab== 'structure' }" @click="setSidebarTab('structure')"><i class="mdi mdi-view_stream"></i><span>Konten</span></div>
    <div class="editor-tab editor-tab-theme" :class="{'is-active': editorState.activeSidebarTab== 'theme' }" @click="setSidebarTab('theme')"><i class="mdi mdi-brush"></i><span>Tema</span></div>
    <div class="editor-tab editor-tab-integration" :class="{'is-active': editorState.activeSidebarTab== 'integration' }" @click="setSidebarTab('integration')"><i class="mdi mdi-dashboard"></i><span>Integrasi</span></div>
    <!-- <div
      class="editor-tab editor-tab-help"
      @click="openLinkInNewTab('https://tutorial.ukm.digital/')"
      >
        <i class="mdi mdi-help"></i>
        <span>Help</span>
    </div> -->
    <!-- <div
      class="editor-tab editor-tab-contact"
      @click="openLinkInNewTab('#')"
      >
        <i class="mdi mdi-phone"></i>
        <span>Contact Support</span>
    </div> -->
  </div>

  <div class="icl-editor__sidebar-tab-content">
    <Pages :class="{'is-active':editorState.activeSidebarTab == 'page' }" />
    <Structure :class="{'is-active': editorState.activeSidebarTab == 'structure'}"/>
    <Theme :class="{'is-active': editorState.activeSidebarTab == 'theme'}"/>
    <Integration v-if="editorState.activeSidebarTab == 'integration'" :class="{'is-active': editorState.activeSidebarTab == 'integration'}"/>
  </div>
</div>
</template>

<script>
import Pages       from '@/components/ui/Pages'
import Structure   from '@/components/ui/Structure'
import Theme       from '@/components/ui/Theme'
import Integration from '@/components/ui/Integration'

export default {
    name: "Sidebar",
    components: {
      Pages,
      Structure,
      Theme,
      Integration
    },
    computed: {
      editorState(){
        return this.$store.state.editorState
      },
    },
    mounted(){
      // this.$store.subscribe((mutation) => {
      //   if ( mutation.type == 'ACTIVE_BLOCK' && mutation.payload == null )
      //     this.$store.commit('activeSidebarTab','structure')
      // })
    },
    methods: {
      setSidebarTab(tab) {
        this.$store.dispatch('setSidebarTab',tab );
        // if ( this.$store.state.editorState.activeSidebarTab == tab )
        //   this.$store.dispatch('setSidebarTab', null );
        // else
      },
      openLinkInNewTab(link) {
        window.open(link, '_blank').focus();
      }
    },

}
</script>


<style lang="less">
.icl-editor{
  &__sidebar{
    width: 480px;
    flex-shrink: 0;
    position: fixed;
    left: 0;
    top: 70px;
    bottom: 0;
    font-size: 14px;
    // font-family: Roboto;
    // background-color: #333;
    z-index: 1;
    transition: .3s ease;

    .preview-mode &{
      transform: translateX(-100%);
    }

    &-tabs{
      display: flex;
      position: absolute;
      flex-direction: column;
      width: 80px;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 1;
      justify-content: flex-start;
      border-top: 1px solid #e3e3e3;
      border-right: 1px solid #e3e3e3;
      div{
        padding          : 20px 10px;
        background-color : transparent;
        cursor           : pointer;
        text-align       : center;
        text-transform   : uppercase;
        font-weight      : 700;
        border           : 1px solid transparent;
        border-left      : none;
        border-right     : none;
        transition: .3s ease;
        
        &:hover{
          background-color: #fbfbfb;
        }
        &:first-child{
          border-top: none!important;
        }
        span{
          font-size: 10px;
          margin-top: 5px;
          display: block;
        }

        &:first-child{
          border-left: none;
        }

        &.is-active{
          color: var(--primary);
          border-color: #e3e3e3;
        }
      }
    }

    &-tab-content{
      position: absolute;
      width: 400px;
      height: 100%;
      left: 80px;
      overflow: auto;
      background-color: #e9ecf1;
      background-color: #f8f9fa;
      box-shadow: 5px 5px 10px rgba(0,0,0,.1);
      border-top: 1px solid #e3e3e3;
      border-right: 1px solid #e3e3e3;
    }
  }

  .structure-sections{
    span{
      display: block;
    }
  }
}

.swal2-popup{
  padding: 0!important;
}
.swal2-title{
  font-size: 18px!important;
}
.swal2-header{
  padding: 20px;
}
.swal2-content{
  font-size: 16px!important;
  padding: 0 20px!important;
}
.swal2-icon{
  width: 4em!important;
  height: 4em!important;
}
.swal2-actions{
  display: flex!important;
  margin: 40px 0 0 !important;

  button{
    flex: 1;
    margin: 0;
    font-size: 14px!important;
    border-radius: 0!important;
    transition: .3s ease;
  }
  .swal2-styled:focus{
    box-shadow: inset 0 0 0 2px rgba(255,255,255,.6)
  }
}
.panel-title{
  font-weight: 300;
  display: flex;
  align-items: center;
  margin-bottom: 15px;

  i{
    margin-right: 15px;
  }
}
.editor-tab {
  position: relative;
  z-index: 1;
  cursor: pointer;
  &:before{
    content: " ";
    width: 5px;
    position: absolute;
    height: 100%;
    left: 0;
    top: 0;
    z-index: -1;
    transition: .3s cubic-bezier(0.78,-0.01, 0.35, 0.99);
  }
  &:hover, &.is-active{
    color: white!important;
    &:before{
      width: 100%;
    }
  }
}
.editor-tab-page{
  &:before{ background-color: #4263eb; }
}
.editor-tab-structure{
  &:before{ background-color: #fd7e14; }
}
.editor-tab-theme{
  &:before{ background-color: #d6336c; }
}
.editor-tab-integration{
  &:before{ background-color: #37b24d; }
}
.editor-tab-help{
  &:before{ background-color: #b1ae00; }
}
.editor-tab-contact{
  &:before{ background-color: #ff0062; }
}
</style>