<script>
import Vue from 'vue'
import store from '@/store'
import EventBus from '@/components/EventBus'
export default {
  name: 'preview-frame',
  render(h) {
    return  h('iframe', {
      attrs: {id:'icl-editor__iframe'},
      class: {
        'icl-editor__preview-iframe': true,
      },
      on: { load: this.renderChildren}
    })
  },

  data() {
    return {
      iframe: null
    }
  },

  beforeUpdate() {
    //freezing to prevent unnessessary Reactifiation of vNodes
    this.iApp.children = Object.freeze(this.$slots.default)
    // this.iApp.children = this.$slots.default

  },

  mounted(){
    // this.iframeDetect();
    this.$store.subscribe((mutation) => {
      if ( mutation.type=="addBlock" ) {
        // const blockID = mutation.payload
        
        this.$store.commit('ACTIVE_BLOCK', mutation.payload.id)
        this.focusBlock(mutation.payload.id,300);

      }
      if ( mutation.type=="ACTIVE_BLOCK" ) {
        this.focusBlock(mutation.payload,1000)
      }

    });

    const iframeWindow = document.getElementById('icl-editor__iframe').contentWindow;
    iframeWindow.addEventListener("dragover",function(e){
      e = e || event;
      e.preventDefault();
    },false);
    iframeWindow.addEventListener("drop",function(e){
      e = e || event;
      e.preventDefault();
    },false);

  },

  methods: {
    focusBlock(id,timeout) {
      const frame = this.$el.contentWindow
      setTimeout(()=>{
        try { frame.document.getElementById('block-'+id).scrollIntoView({ behavior: 'smooth', block: 'start' }) }
        catch(error) { 
          // console.log(error) 
          return false;
        }
      },timeout)
    },
    iframeDetect(){
      document.getElementById('icl-editor__iframe').addEventListener('mouseover',()=>{
         this.$store.commit('iframeMouseOver', true);
      });
      document.getElementById('icl-editor__iframe').addEventListener('mouseout',()=>{
          this.$store.commit('iframeMouseOver', false);
      });

      window.addEventListener('blur', ()=>{
        if(this.$store.state.editorState.iframeMouseOver && this.$store.state.editorState.activeSidebarTab !== null){
          this.$store.commit('activeSidebarTab',null)
        }
      })
    },
    loadStylesheets(styles) {
      const doc = this.$el.contentWindow.document
      const head = doc.getElementsByTagName('head')[0]

      return Promise.all(
        styles.map( style => {
          const link = document.createElement('link');
          link.rel = "stylesheet";
          link.href = style

          head.appendChild( link )

          return new Promise( (resolve) => {
            link.onload = function(){
              // console.log('loaded', style);
              resolve();
            }

          } )
        })
      );
    },

    loadScripts(scripts) {
      const doc = this.$el.contentWindow.document
      const head = doc.getElementsByTagName('head')[0]

      // scripts.map( style => {
      //   const link = document.createElement('script');
      //   link.src = style

      //   head.appendChild( link )
      // })

      return Promise.all(
        scripts.map( script => {
          const jstag = document.createElement('script');
          jstag.src = script
          head.appendChild( jstag )

          return new Promise( (resolve) => {
            jstag.onload = function(){
              // console.log('loaded', script);
              resolve();
            }

          } )
        })
      )
    },

    loadAssets() {
      const styles = this.loadStylesheets([
        `${this.$root.config.path}/blocks-assets/css/fontawesome.min.css`,
        `${this.$root.config.path}/blocks-assets/css/editor-preview.css`,
        `${this.$root.config.path}/blocks-assets/css/material-icons.min.css`,
        `${this.$root.config.path}/blocks-assets/css/global.css`,
      ]);

      const scripts = this.loadScripts([
        // `https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js`,
        `${this.$root.config.path}/blocks-assets/js/editor-preview.js`,
        `${this.$root.config.path}/blocks-assets/js/frontend.js`,
      ])

      Promise.all([styles,scripts])
        .then( () =>{
          // console.log( 'all assets loaded' );
          this.$el.contentWindow.postMessage({
            action: 'update-style',
            payload: this.$store.state.project.style
          },'*')

          this.$el.contentDocument.body.classList.add('loaded')
        } )
    },

    renderChildren() {
      const children = this.$slots.default
      const body = this.$el.contentDocument.body
      const el = document.createElement('DIV') // we will mount or nested app to this element
      const loader = document.createElement('DIV')
      el.id = "site-preview"
      loader.classList.add( 'preview-loader', 'loader' )

      // const doc = this.$el.contentWindow.document
      const frame = this.$el.contentWindow
      // const head = doc.getElementsByTagName('head')[0]

      // body.style.opacity = 0

      frame.addEventListener('drop', function(e){
        e.preventDefault();
        e.stopPropagation();
      }, false)

      // this.loadStylesheets([
      //   "https://fonts.googleapis.com/css?family=Lato",
      //   "https://use.fontawesome.com/releases/v5.3.1/css/all.css",
      //   "https://fonts.googleapis.com/icon?family=Material+Icons",
      //   'https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/tiny-slider.css',
      //   `${this.$root.config.path}/blocks-assets/css/bulma.min.css`,
      //   `${this.$root.config.path}/blocks-assets/css/fontawesome.min.css`,
      //   `${this.$root.config.path}/blocks-assets/css/editor-preview.css`,
      //   `${this.$root.config.path}/blocks-assets/css/frontend.css`,
      //   `${this.$root.config.path}/blocks-assets/css/material-icons.min.css`
      // ]);

      // this.loadScripts([
      //   // `https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js`,
      //   `${this.$root.config.path}/blocks-assets/js/editor-preview.js`,
      //   `${this.$root.config.path}/blocks-assets/js/frontend.js`,
      // ]).then( () =>{
      //   console.log('loaded');
      // } )

      this.loadAssets();

      body.appendChild(loader)
      body.appendChild(el)

      const iApp = new Vue({
        store,
        name: 'iApp',
        //freezing to prevent unnessessary Reactifiation of vNodes
        data: { children: Object.freeze(children) },
        // data: { children },
        render(h) {
          return h( 'div', 
            { class: "icl-editor__block-wrapper" },
            this.children
          )
        },
      })

      iApp.$mount(el) // mount into iframe

      // Store watcher
      this.$store.subscribe((mutation) => {
        if (mutation.type == "addBlock") {
          if ( mutation.payload.hasOwnProperty('runScript') )
            frame.postMessage({
              action: mutation.payload.runScript,
              payload: mutation.payload
            },'*')
        }

        if ( mutation.type=="previewSize" ) {
          frame.postMessage({
            action: "window-resized"
          },'*')
        }

        if ( mutation.type == "updateStyle" ) {
          // console.log(mutation.payload);
          frame.postMessage({
            action: 'update-style',
            payload: mutation.payload
          },'*')
        }

        if ( mutation.type == "previewMode" ) {
          frame.postMessage({
            action: 'preview-mode',
            payload: mutation.payload
          },'*')
        }

        if ( mutation.type == "UPDATE_SITE_META" ) {
          if ( mutation.payload.field == "custom_style" ) {
            frame.postMessage({
              action: 'custom-style',
              payload: mutation.payload.value
            },'*')
          }
        }

      })

      // EventBus Events
      EventBus.$on('update-carousel', (payload)=>{
        frame.postMessage({
          action: "update-carousel",
          payload
        },'*')
      });

      EventBus.$on('carousel-mounted', (payload)=>{
        frame.postMessage({
          action: "carousel-mounted",
          payload
        },'*')
      })


      this.iApp = iApp // cache instance for later updates
    }
  }
}
</script>