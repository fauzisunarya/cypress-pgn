import Vue from 'vue'
import Vuex from 'vuex'
import Axios from 'axios'
import findIndex from 'lodash/findIndex'
import cloneDeep from 'lodash/cloneDeep'
import isEmpty from 'lodash/isEmpty'
import map from 'lodash/map'
import qs from 'qs';
import randomId from 'random-id'

Vue.use(Vuex)

const config = window.builder_vars

export default new Vuex.Store({
  state: {
    editorState: {
      activeSidebarTab: 'page',
      activeOptionDomain: 'content',
      selectedBlock: null,
      activeBlock: null,
      activeOption: null,
      activeBlockCategory: '*',
      previewSize: 'desktop',
      previewMode: false,
      iframeHeight: 0,
      iframeMouseOver: false,
      loading: false,
      dirty: false,
      saving: false,
      editorReady: false,
      blocks: [],
      selectedMenu: null,
      openPagePersonalization: false,
      activePersonalizationTab: 'user-tag'
    },
    editing: null,
    project: {},
    savedBlocks: [],
    templates: [],
  },
  mutations: {
    // form(state, payload){
    //   state.form = payload
    // },
    SET_FORM(state, payload){
      state.project.forms = payload
    },
    ADD_FORM(state, payload){
      state.project.forms.push(payload)
    },
    UPDATE_FORM(state, payload) {
      Vue.set(state.project.forms, payload.index, payload.value)
    },
    REMOVE_FORM(state, payload) {
      const index = findIndex(state.project.forms, i => i.form_id == payload)
      state.project.forms.splice(index, 1)
    },
    UPDATE_SITE_META(state, payload){
      state.project.meta[payload.field] = payload.value;
      state.editorState.dirty= true
    },
    editing(state,payload){
      state.editing = payload
    },
    selectedMenu(state,payload){
      state.editorState.selectedMenu = payload
    },
    templates(state, payload){
      state.templates = payload
    },
    previewMode(state,payload){
      state.editorState.previewMode = payload
    },
    project(state, payload){
      state.project = payload
    },
    addMenu(state, payload) {
      state.project.menus.push(payload)
    },
    removeMenu(state, payload) {
      const index = findIndex( state.project.menus, i=> i.menuId == payload )
      state.project.menus.splice(index, 1)
      state.editorState.selectedMenu = null
    },
    setActiveBlockCategory(state, payload){
      state.editorState.activeBlockCategory = payload
    },
    updateBlockData(state,payload){
      state.editorState.blocks = payload
    },
    editorReady(state,payload){
      state.editorState.editorReady = payload
    },
    dirty(state, payload){
      state.editorState.dirty = payload
    },
    loading(state, payload){
      state.editorState.loading = payload
    },
    saving(state, payload){
      state.editorState.saving = payload
    },
    UPDATE_USER_BLOCK(state, payload) {
      state.project.user_blocks = payload
    },
    REMOVE_USER_BLOCK(state, payload) {
      const index = findIndex( state.project.user_blocks, i=> i.id == payload )
      state.project.user_blocks.splice( index, 1 )
    },
    REMOVE_USER_TEMPLATE( state, payload ) {
      const index = findIndex( state.project.user_templates, i=> i.id == payload )
      state.project.user_templates.splice( index, 1 )
    },
    UPDATE_USER_TEMPLATE(state, payload) {
      state.project.user_templates = payload
    },
    updatePageMeta(state, payload){
      const id    = payload.id
      const index = findIndex(state.project.pages, i=>i.id == id)

      state.project.pages[index].title       = payload.title,
      state.project.pages[index].description = payload.description,
      state.project.pages[index].slug        = payload.slug,
      state.project.pages[index].hidden      = payload.hidden,
      state.project.pages[index].label       = payload.label

    },
    updatePage( state, payload ) {
      const id    = payload.id
      const index = findIndex(state.project.pages, i=>i.id == id)
      const page  = cloneDeep(state.project.pages[index]);

      page.title       = payload.title,
      page.description = payload.description,
      page.slug        = payload.slug,
      page.hidden      = payload.hidden,
      page.label       = payload.label

      state.project.pages = [
        ...state.project.pages.slice(0,index),
        page,
        ...state.project.pages.slice(index+1)
      ]
      // state.project.pages[index] = {
      //   ...state.project.pages[index],
      //   title       : payload.title,
      //   description : payload.description,
      //   slug        : payload.slug,
      //   hidden      : payload.hidden,
      //   label       : payload.label
      // },
    },
    updatePagePersonalization( state, payload ) {
      const id    = payload.id
      const index = findIndex(state.project.pages, i=>i.id == id)
      const page  = cloneDeep(state.project.pages[index]);

      page.personalization = payload.personalization;

      state.project.pages = [
        ...state.project.pages.slice(0,index),
        page,
        ...state.project.pages.slice(index+1)
      ]
    },
    activeSidebarTab( state, payload ) {
      state.editorState.activeSidebarTab = payload
    },
    activeOptionDomain(state, payload) {
      state.editorState.activeOptionDomain = payload
    },
    previewSize( state, payload ) {
      state.editorState.previewSize = payload
    },
    pageBlocks( state, payload ) {
      const index = findIndex(state.project.pages, i=>i.id == state.editing )
      state.project.pages[index].blocks = payload
      state.editorState.dirty = true
    },
    siteMenu( state, payload ) {
      state.project.menus = payload
      // state.editorState.dirty = true
    },
    addBlock( state, payload ) {
      const index = findIndex(state.project.pages, i=>i.id == state.editing )
      state.project.pages[index].blocks.push(payload)
    },
    removeBlock( state, payload ) {
      const index = findIndex(state.project.pages, i=>i.id == state.editing )
      state.project.pages[index].blocks.splice(payload, 1)
      state.editorState.dirty = true
    },
    UPDATE_PAGE( state, payload ) {
      const index = findIndex(state.project.pages, i=>i.id == state.editing )
      // console.log( payload )
      state.project.pages[index].blocks = payload
    },
    REMOVE_ALL_BLOCK( state ) {
      const index = findIndex(state.project.pages, i=>i.id == state.editing )
      state.project.pages[index].blocks = []
      state.editorState.dirty = true
    },
    setIframeHeight( state, payload ) {
      state.editorState.iframeHeight = payload
    },
    SELECTED_BLOCK( state, payload ) {
      state.editorState.selectedBlock = payload
    },
    ACTIVE_BLOCK( state, payload ) {
      state.editorState.activeBlock = payload
    },
    iframeMouseOver(state, payload) {
      state.editorState.iframeMouseOver = payload
    },
    deletePage(state, payload){
      const index = findIndex(state.project.pages, i=>i.id == payload )
      state.project.pages.splice(index,1)
      state.editorState.selectedMenu = null
    },
    addPage(state, payload) {
      state.project.pages.push(payload)
    },
    deleteMenu(state, payload) {
      const index = findIndex(state.project.menus, i=> {
        return i.type == 'page' && i.id == payload
      });
      state.project.menus.splice(index, 1)
    },
    setHomepage(state, payload) {
      state.project.homepage = payload
    },
    SET_POST_INDEX(state, payload) {
      state.project.post_index = payload
    },
    SET_POST_SINGLE(state, payload) {
      state.project.post_single = payload
    },
    SET_CATALOG_INDEX(state, payload) {
      state.project.catalog_index = payload
    },
    SET_CATALOG_SINGLE(state, payload) {
      state.project.catalog_single = payload
    },
    SET_MENU_HOMEPAGE ( state, payload ) {
      const index = findIndex( state.project.menus, i=> i.type == "page" && i.id == payload )
      const value = state.project.menus[index]
      value.homepage = true

      Vue.set(state.project.menus, index, value)
    },
    SET_MENU_POST_INDEX ( state, payload ) {
      const index = findIndex( state.project.menus, i=> i.type == "page" && i.id == payload )
      const value = state.project.menus[index]
      value.post_index = true

      Vue.set(state.project.menus, index, value)
    },
    SET_MENU_POST_SINGLE ( state, payload ) {
      const index = findIndex( state.project.menus, i=> i.type == "page" && i.id == payload )
      const value = state.project.menus[index]
      value.post_single = true

      Vue.set(state.project.menus, index, value)
    },
    updateStyle(state, payload) {
      state.project.style = payload
    },
    openPagePersonalization(state, payload) {
      state.editorState.openPagePersonalization = payload
    },
    activePersonalizationTab(state, payload) {
      state.editorState.activePersonalizationTab = payload
    },
  },
  actions: {
    setSidebarTab( {commit}, payload ) {
      commit( 'activeSidebarTab', payload )
    },

    setPreviewSize( {commit}, payload ) {
      commit( 'previewSize', payload )
    },

    updatePageBlocks( {commit}, payload ) {
      commit( 'pageBlocks', payload )
    },

    addPageBlock( {commit}, payload ) {
      commit( 'addBlock', payload );
      commit('dirty', true)
    },

    loadBlocks( { commit } ) {
      return new Promise( ( resolve, reject ) => {
        Axios.get( `${config.api}/blocks` )
          .then( response => {
            const blockData = response.data.map( item => {
              item.screenshot = config.path + '/blocks/' + item.screenshot
              return item
            } )
            commit('updateBlockData', blockData)
            resolve();
          } )
          .catch( error => {
            reject(error);
          } )
      } )
    },

    loadBlockTemplate() {
      return new Promise( (resolve, reject) => {
        Axios.get(`${config.api}/block-templates`)
          .then( template => {
            // Add template script
            document.getElementsByTagName("BODY")[0].insertAdjacentHTML("beforeend",template.data);
            resolve();
          })
          .catch(error => {
            reject(error)
          })
      } )
    },

    loadBlockStyles() {
      return new Promise( (resolve, reject) => {
        Axios.get(`${config.api}/block-styles`)
          .then( template => {
            // Add template script
            const blockStyle = document.createElement('style');
            const iframe     = document.getElementById('icl-editor__iframe');

            blockStyle.innerHTML = template.data
            iframe.contentDocument.head.appendChild(blockStyle);

            resolve();
          })
          .catch(error => {
            reject(error)
          })
      } )
    },

    loadSavedBlock( {commit, state}, payload ) {
      return new Promise( (resolve, reject)=>{
        Axios.get(config.api + "/blocks/user")
          .then( response => {
            commit('UPDATE_USER_BLOCK', response.data.content);
            resolve();
          } )
          .then( error => {
            commit( 'UPDATE_USER_BLOCK', [] );
            reject();
          } )
      } )
    },

    loadProject({commit, state}, payload){
      return new Promise( (resolve, reject) => {
        Axios.get( config.api + "/" + config.extra.site_id)
          .then( response => {
            const pages     = response.data.pages.map(item=> prepareProject(state, item));
            const tempPages = cloneDeep(response.data.pages)
            let menus     = response.data.menus
            // Generate menu if empty
            if ( isEmpty(menus) ){

              try {
                menus = tempPages.map(item=>{
                  delete item.blocks;
                  item.hidden   = false;
                  item.type     = "page";
                  item.menuId   = randomId(6,'aA0');
                  item.homepage = response.data.homepage == item.id

                  return item;
                })
              } catch ( error ){
                reject( {error, serverResponse: btoa(JSON.stringify(response.data))} )
              }
            } else {
              // check pages againts menu
              response.data.pages.map( page=>{
                const menu = findIndex(menus, i=>i.id == page.id)
                if ( menu == -1 ) {
                  menus.push({
                    id          : page.id,
                    type        : "page",
                    title       : page.title,
                    description : page.description,
                    slug        : page.slug,
                    label       : page.label,
                    hidden      : page.hidden
                  })
                }
              } )

              menus.map( (menu,index) => {
                if ( menu.type == "page" ) {
                  const page = findIndex(response.data.pages, i=>i.id == menu.id)
                  
                  if ( page == -1 )
                    menus.splice(index, 1)
                }

                if ( !menu.menuId )
                  menu.menuId = randomId(6,'aA0')
              } )
            }

            // console.log('generated menu: ',menus);

            const styleSettings = [
              {
                "label": "Warna",
                "settings": {
                  "primaryColor": {
                    type: 'color',
                    horizontal: true,
                    label: 'Utama',
                    value: '#007bff'
                  },
                  "headingColor": {
                    type  : 'color',
                    horizontal: true,
                    label : 'Judul',
                    value : '#333333'
                  },
                  "textColor": {
                    type  : 'color',
                    horizontal: true,
                    label : 'Teks',
                    value : '#4a4a4a'
                  },
                  "metaColor": {
                    type  : 'color',
                    horizontal: true,
                    label : 'Teks detail',
                    value : '#888'
                  },
                }
              },
              {
                "label": "Font",
                "settings": {
                  "headingFont": {
                    type: 'typography',
                    horizontal: true,
                    label: 'Judul',
                    value: 'Open Sans'
                  },
                  // "headingFontWeight": {
                  //   type: 'select',
                  //   horizontal: true,
                  //   label: 'Ketebalan Judul',
                  //   value: '700',
                  //   options: {
                  //     '300' : 'Thin',
                  //     '400' : 'Normal',
                  //     '500' : 'Medium',
                  //     '700' : 'Bold',
                  //     '900' : 'Bolder'
                  //   }
                  // },
                  "textFont": {
                    type: 'typography',
                    horizontal: true,
                    label: 'Teks',
                    value: 'Open Sans'
                  },
                  "quoteFont": {
                    type       : 'typography',
                    horizontal : true,
                    label      : 'Kutipan',
                    value      : 'Playfair Display'
                  },
                  "textFontSize": {
                    type       : 'slider',
                    horizontal : true,
                    label      : 'Ukuran teks global',
                    value      : 16,
                    min        : 10,
                    max        : 24
                  },
                }
              }
            ]

            // Generate style if empty
            if ( isEmpty(response.data.style) ) {
              response.data.style = styleSettings
            } else {

              const updatedValue = styleSettings.map( (section, index) => {
                map( section.settings, (field, key) => {
                  if ( ! isEmpty( response.data.style[index]['settings'][key] ) )
                    styleSettings[index]['settings'][key]['value'] = response.data.style[index]['settings'][key]['value']
                } );

                return section;
              } )

              response.data.style = updatedValue

            }

            // generate site meta
            if ( isEmpty(response.data.meta) ) {
              response.data.meta = {
                custom_style: null,
                custom_script: null,
                google_analytics: null,
                google_tagmanager: null,
                google_searchconsole: null,
                facebook_pixel: null,
                whatsapp_button: null
              }
            }

            // Update project
            // console.log(response.data)
            commit('project', {...response.data, pages})
            commit('updateStyle', response.data.style )
            commit('UPDATE_SITE_META', {
              field: 'custom_style',
              value: response.data.meta.custom_style
            });
            commit('siteMenu', menus )

            // Set current editing to homepage or first item
            const homepageIndex = findIndex( state.project.pages, page=>page.id == response.data.homepage )
            
            if ( homepageIndex !== -1 )
              commit('editing', state.project.pages[homepageIndex].id)
            else
              if ( state.project.pages.length )
                commit('editing', state.project.pages[0].id)
              else
                commit('editing', null)

            commit('editorReady', true);

            resolve();
          })
          .catch(error => {
            console.log("Error getting project", error);
            reject(error);
          })

      } )
    },

    loadTemplate({commit, state}, payload) {
      // if there are more than 1 page (for example: product-page)
      if (payload.length > 1) {
        if (
          (payload[0].slug.includes("produk-ukm") && state.project.pages.find((page) => !page.slug.includes("produk-ukm")))
          ||
          (payload[0].slug.includes("artikel-ukm") && state.project.pages.find((page) => !page.slug.includes("artikel-ukm")))
        ) {
          // if there are no article pages or product pages
          for (let i = 0; i < payload.length; i++) {
            const preparePage = prepareProject(state, payload[i]);
            commit('UPDATE_PAGE', preparePage.blocks);
          }
        }
      } else {
        // single page
        const preparePage = prepareProject(state, payload);
        commit('UPDATE_PAGE', preparePage.blocks);
      }
    },

    saveMeta({commit, state, dispatch}, payload) {
      const data = {
        site_id: config.extra.site_id,
        meta: JSON.stringify(state.project.meta)
      }
      const opts = {
        method          : 'POST',
        headers         : { 'content-type': 'application/x-www-form-urlencoded' },
        data            : qs.stringify(data),
        url             : config.api+'/site-meta',
        withCredentials : true
      }
      return new Promise((resolve, reject)=>{
        Axios(opts)
          .then(response=>{
            // console.log(response)
            resolve()
          })
          .catch(error=>{
            // console.log(error);
            reject();
          })

      })
    },

    saveMenu({commit, state, dispatch}, payload){
      const menus = state.project.menus.map(item => {
        if ( item.type=="page" )
          item.homepage = state.project.homepage == item.id

        return item
      })
      const data = {
        site_id: config.extra.site_id,
        menus: JSON.stringify(menus)
      }
      const opts = {
        method          : 'POST',
        headers         : { 'content-type': 'application/x-www-form-urlencoded' },
        data            : qs.stringify(data),
        url             : config.api+'/site-menu',
        withCredentials : true
      }
      return new Promise((resolve, reject)=>{
        Axios(opts)
          .then(response=>{
            commit('siteMenu', response.data.menu)
            resolve()
          })
          .catch(error=>{
            console.log(error);
            reject();
          })

      })
    },

    saveStyle({commit, state, dispatch}, payload){
      const data = {
        site_id: config.extra.site_id,
        style: JSON.stringify(state.project.style)
      }
      const opts = {
        method          : 'POST',
        headers         : { 'content-type': 'application/x-www-form-urlencoded' },
        data            : qs.stringify(data),
        url             : config.api+'/site-style',
        withCredentials : true
      }
      return new Promise((resolve, reject)=>{
        Axios(opts)
          .then(response=>{
            commit('updateStyle', response.data.style)
            resolve()
          })
          .catch(error=>{
            console.log(error);
            reject();
          })

      })
    },

    deleteMenu({commit, state, dispatch}, payload) {
      commit('deleteMenu', payload)
      dispatch('saveMenu')
    },

    deletePage({commit, state,dispatch}, payload){
      const pageIndex = findIndex(state.project.pages, i=>i.id == payload)
      const opts = {
        method          : 'DELETE',
        headers         : { 'content-type': 'application/x-www-form-urlencoded' },
        url             : config.api+'/page/'+payload,
        withCredentials : true
      }
      return new Promise( (resolve, reject) => {
        return Axios(opts)
          .then(response=>{
            if ( response.data.status ) {
              commit('deletePage', payload )
              dispatch('deleteMenu', payload)
              // If deleted menu is current editing page
              // Move editing to first page 
              if (payload == state.editing)
                commit('editing', state.project.pages[0].id)
              resolve()
            } else {
              console.log(response.data.message)
              reject()
            }
          })
          .catch(error=>{
            console.log(error);
            reject()
          })

      } )
    },

    getUserTemplates({commit}){
      return Axios.get(config.api + "/templates/user")
        .then( response => {
          commit('UPDATE_USER_TEMPLATE', response.data.content)
        })
    },

    getTemplates({commit}){
      return new Promise ( (resolve, reject) => {
        return Axios.get(config.api+"/templates")
          .then( response=>{
            commit('templates',response.data);
            resolve();
          } )
          .catch( error => {
            reject(error);
          } )
      } )
    },

    addPage({commit, state,dispatch}, payload){
      const data = {
        'title'         : payload.title,
        'description'   : payload.description,
        'label'         : payload.label,
        'blocks'        : JSON.stringify(payload.blocks),
        ...config.extra
      };

      if ( payload.slug )
        data.slug = payload.slug

      const options = {
        method          : 'POST',
        headers         : { 'content-type': 'application/x-www-form-urlencoded' },
        data            : qs.stringify(data),
        url             : config.api+'/page',
        withCredentials : true
      };

      return new Promise((resolve, reject)=>{
        return Axios(options)
          .then( response => {
            commit('saving', false);

            const preparePage = prepareProject(state, payload)
            preparePage.id   = response.data.id;
            preparePage.slug = response.data.slug;

            commit('addPage', preparePage)

            // add it to menu
            commit('addMenu', {
              "type"        : 'page',
              "menuId"      : randomId(6,'aA0'),
              "id"          : response.data.id,
              "label"       : payload.label,
              "slug"        : response.data.slug,
              "title"       : payload.title,
              "description" : payload.description,
              "hidden"      : response.data.slug.includes("detail-produk-ukm") || response.data.slug.includes("detail-artikel-ukm") ? true : false,
            });

            dispatch('saveMenu')
            commit('saving', false);

            if (payload.slug.includes("-ukm")) {
              if (payload.slug.includes("detail-produk-ukm")) {
                dispatch('setCatalogSingle', response.data.id)
                  .then(() => {
                    resolve();
                  })
                  .catch((error) => {
                    reject(error);
                  });
              } else if (payload.slug.includes("daftar-produk-ukm")) {
                dispatch('setCatalogIndex', response.data.id)
                  .then(() => {
                    resolve();
                  })
                  .catch((error) => {
                    reject(error);
                  });
              } else if (payload.slug.includes("detail-artikel-ukm")) {
                dispatch('setPostSingle', response.data.id)
                  .then(() => {
                    resolve();
                  })
                  .catch((error) => {
                    reject(error);
                  });
              } else if (payload.slug.includes("daftar-artikel-ukm")) {
                dispatch('setPostIndex', response.data.id)
                  .then(() => {
                    resolve();
                  })
                  .catch((error) => {
                    reject(error);
                  });
              } else {
                // if the user page name includes "-ukm"
                resolve();
              }
            } else {
              resolve();
            }
          })
          .catch( error => {
            commit('saving', false);
            reject(error);
          } )

      })
    },

    setHomepage({commit}, payload){
      const data = {
        'site_id'       : config.extra.site_id,
        'page_id'       : payload,
      };

      const options = {
        method          : 'POST',
        headers         : { 'content-type': 'application/x-www-form-urlencoded' },
        data            : qs.stringify(data),
        url             : config.api+'/page/home',
        withCredentials : true
      };

      return new Promise( (resolve, reject) => {
        return Axios(options)
          .then( () =>{
            commit('setHomepage', payload)
            commit('SET_MENU_HOMEPAGE', payload)
            resolve()
          } )
          .catch( error=>{
            console.log("error setting homepage", error)
            reject( error )
          })
      } )
    },

    setPostIndex({commit,state}, payload){
      const endpoint = state.project.post_index !== payload ? "page/post-index" : "page/unset-post-index"
      const data = {
        'site_id'       : config.extra.site_id,
        'page_id'       : payload,
      };
      
      const options = {
        method          : 'POST',
        headers         : { 'content-type': 'application/x-www-form-urlencoded' },
        data            : qs.stringify(data),
        url             : config.api+'/'+endpoint,
        withCredentials : true
      };
      return new Promise( (resolve, reject) => {
        Axios(options)
          .then( () =>{
            if ( payload === state.project.post_single && state.project.post_index !== payload ) { commit('SET_POST_SINGLE', null) }

            commit('SET_POST_INDEX', state.project.post_index !== payload ? payload : null)
            commit('SET_MENU_POST_INDEX', payload)

            return resolve()
          } )
          .catch( error=>{
            console.log("error setting post index", error)
            return reject( error )
          })
      } )
    },

    setPostSingle({commit, state}, payload){
      const endpoint = state.project.post_single !== payload ? "page/post-single" : "page/unset-post-single"
      const data = {
        'site_id'       : config.extra.site_id,
        'page_id'       : payload,
      };

      const options = {
        method          : 'POST',
        headers         : { 'content-type': 'application/x-www-form-urlencoded' },
        data            : qs.stringify(data),
        url             : config.api+'/'+endpoint,
        withCredentials : true
      };
      return new Promise( (resolve, reject) => {
        Axios(options)
          .then( () =>{
            if ( payload == state.project.post_index && state.project.post_single !== payload ) { commit('SET_POST_INDEX', null) }

            commit('SET_POST_SINGLE', state.project.post_single !== payload ? payload : null)
            commit('SET_MENU_POST_SINGLE', payload)

            resolve()
          } )
          .catch( error=>{
            console.log("error setting Post Single", error)
            reject(error)
          })
      })
    },

    setCatalogIndex({commit,state}, payload){
      const endpoint = state.project.catalog_index !== payload ? "page/catalog-index" : "page/unset-catalog-index"
      const data = {
        'site_id'       : config.extra.site_id,
        'page_id'       : payload,
      };
      
      const options = {
        method          : 'POST',
        headers         : { 'content-type': 'application/x-www-form-urlencoded' },
        data            : qs.stringify(data),
        url             : config.api+'/'+endpoint,
        withCredentials : true
      };
      return new Promise( (resolve, reject) => {
        Axios(options)
          .then( () =>{
            if ( payload === state.project.catalog_single && state.project.catalog_index !== payload ) { commit('SET_CATALOG_SINGLE', null) }

            commit('SET_CATALOG_INDEX', state.project.catalog_index !== payload ? payload : null)
            commit('SET_MENU_CATALOG_INDEX', payload)

            return resolve()
          } )
          .catch( error=>{
            console.log("error setting catalog index", error)
            return reject( error )
          })
      } )
    },

    setCatalogSingle({commit, state}, payload){
      const endpoint = state.project.catalog_single !== payload ? "page/catalog-single" : "page/unset-catalog-single"
      const data = {
        'site_id'       : config.extra.site_id,
        'page_id'       : payload,
      };

      const options = {
        method          : 'POST',
        headers         : { 'content-type': 'application/x-www-form-urlencoded' },
        data            : qs.stringify(data),
        url             : config.api+'/'+endpoint,
        withCredentials : true
      };
      return new Promise( (resolve, reject) => {
        Axios(options)
          .then( () =>{
            if ( payload == state.project.catalog_index && state.project.catalog_single !== payload ) { commit('SET_CATALOG_INDEX', null) }

            commit('SET_CATALOG_SINGLE', state.project.catalog_single !== payload ? payload : null)
            commit('SET_MENU_CATALOG_SINGLE', payload)

            resolve()
          } )
          .catch( error=>{
            console.log("error setting Catalog Single", error)
            reject(error)
          })
      })
    },

    setLandingTemplate({commit}, payload){
      const data = {
        'site_id'       : config.extra.site_id,
        'page_id'       : payload,
      };

      const options = {
        method          : 'POST',
        headers         : { 'content-type': 'application/x-www-form-urlencoded' },
        data            : qs.stringify(data),
        url             : config.api+'/page/landing',
        withCredentials : true
      };
      return new Promise ((resolve, reject) => {
        return Axios(options)
          .then( response=>{
            resolve(response.data);
          } )
          .catch( error=>{
            reject(error);
          })

      })
    },
  }
})


function prepareProject( state, data ) {
  // return console.log(data);
  const blocks =  map( data.blocks, block => {

    // Available Blocks
    const blockData = state.editorState.blocks;
    // Get index of available blocks from this block
    const index     = findIndex( blockData, i => i.blockID == block.blockID )


    // If component type is not availabel on elementData return invalid element
    if ( index == -1 )
      return {
        "blockID":"invalid",
        "id" : new Date().getTime(),
        "title":"Invalid Block",
        "template":"<div class='invalid-block'><h2>Blok tidak valid</h2><p>Blok ini sudah tidak tersedia lagi untuk anda atau sudah dihapus dari sistem</p></div>",
      }

    // Get Block data structure
    const structure = cloneDeep( blockData[index] );
    
    // Set key
    structure.id  = block.id
    structure.name = block.name

    // Applying saved setting value into structure setting
    map( structure.data, (setting,key)=>{
      // setting.value =

      switch( setting.type ){
        case "repeatable" :
          const settingFields = cloneDeep(setting.settings)

          const values = map( block.data[key].value, (items, key) => {

            map( items, (item, key) => {
              
              const value = {
                ...settingFields[key],
                value: item.value
              }

              if ( settingFields[key] && settingFields[key].type == "button" ) {
                if ( !value.value.hasOwnProperty('enable') )
                  value.value.enable = true;
              }

              // Update setting field value;
              items[key] = value;

            } );

            return items;

          } );

          if ( block.data[key] ) {
            setting.value = values
          }


          break
        case "spacer":
          break;
        case "button": 

          setting.value = block.data[key].value;
          
          if ( !setting.value.hasOwnProperty('enable') )
            setting.value.enable = true;

          break;
        default:
          // Check if saved data has value
          if ( block.data[key] ) {
            setting.value = block.data[key].value
          }
          break;

      }

      // console.log( setting );

      return setting
    } )

    // Applying saved setting value into structure setting
    map( structure.style, (style, key)=>{
      // console.log("ahahaha", block.style[key].value)

      switch( style.type ){
        case "repeatable" :
          style.data = block.data[key].data
          break;
        case "spacer":
          break;
        default:
          // Check if saved data has value
          if ( block.style[key] !== undefined ) {
            style.value = block.style[key].value
          }

      }

      return style
    } )

    return structure
  } )

  return {
    ...data,
    blocks
  }
}