<template>
  <div v-if="posts.length">
    <div class="ukm-posts" v-if="mode == 'list'">
      <article class="ukm-post--list" v-for="post in posts">
        <div v-if="post.attachment" :class="`ukm-post--image --size-${image_size || '1by1'}`">
          <figure>
            <img :src="post.attachment" alt="">
          </figure>
        </div>
        <div class="ukm-post--detail">
          <h2 :style="{fontSize: `${title_size}px`}"><a href="#">{{post.title}}</a></h2>
          <div class="date"><i class="mdi mdi-today"></i> {{formatDate(post.created_date)}}</div>
          <div :style="{fontSize: `${excerpt_size}px`}" class="excerpt" v-html="getExcerpt(post.body, excerpt_length)"></div>

          <Button :data="button" />
        </div>
      </article>
    </div>

    <div v-else class="ukm-posts layout-grid" :data-columns="column">
      <article class="ukm-post--grid" v-for="post in posts">
        <div class="ukm-post--inner">
          <div v-if="post.attachment" :class="`ukm-post--image --size-${image_size || '1by1'}`">
            <figure>
              <img :src="post.attachment" alt="">
            </figure>
          </div>
          <div class="ukm-post--detail">
            <h2 :style="{fontSize: `${title_size}px`}"><a href="#">{{post.title}}</a></h2>
            <div class="date"><i class="mdi mdi-today"></i> {{formatDate(post.created_date)}}</div>
            <div :style="{fontSize: `${excerpt_size}px`}" class="excerpt" v-html="getExcerpt(post.body, excerpt_length)"></div>

            <Button :data="button" />
          </div>
        </div>
      </article>
    </div>
  </div>
  <div v-else class="ukm-posts--empty">
    <h2>Anda belum memiliki posting</h2>
    <p>Yuk mulai menambahkan posting untuk konten blog, berita atau artikel di situs anda.</p>
    <img :src="`${config.path}/blocks-assets/imgs/site-post.jpg`" alt="Site post" class="site-post-instruction" style="box-shadow: 0 10px 30px -5px rgba(0,0,0,.1);margin: 30px 0;">
  </div>

</template>

<script>
import Button from '@/components/elements/Button'
import axios from 'axios'
export default{
  name: 'c-post',
  components: {Button},
  props: {
    mode           : { type: String },
    button         : { type: Object },
    image_size     : { type: String },
    title_size     : { type: Number },
    excerpt_size   : { type: Number },
    excerpt_length : { type: Number },
    per_page       : { type: Number },
    column         : { type: Number }
  },
  computed: {
    posts(){
      return this.$store.state.project.posts.slice(0,this.per_page || 3)
    },
    postURL(){
      const url = window.location.href.replace('builder', 'post');
      return url;
    },
    config() {
      return window.builder_vars
    }
  },
  methods: {
    getExcerpt( data, length ) {
      var div = document.createElement("div");
      div.innerHTML = data;
      var text = div.textContent || div.innerText || "";

      return text.split(" ").splice(0,length).join(" ");
    },
    formatDate(date) {
      const d = new Date(date)
      const dtf = new Intl.DateTimeFormat('id', { year: 'numeric', month: 'short', day: '2-digit' }) 
      const [{ value: mo },,{ value: da },,{ value: ye }] = dtf.formatToParts(d) 

      return `${mo} ${da} ${ye}`
    }
  }
}
</script>