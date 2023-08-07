<template>
  <div v-if="posts.length">

    <div class="ukm-posts layout-grid" :data-columns="column">
      <article class="ukm-post--grid" v-for="post in posts" :key="'catalog' + post.id">
        <div class="ukm-post--inner">
          <div v-if="post.thumbnail" :class="`ukm-post--image --size-${image_size || '1by1'}`">
            <figure :class="figure_classname && post.price_before ? figure_classname : ''">
              <!-- product img -->
              <img :src="post.thumbnail.medium" alt="" :style="{borderRadius: image_corner + '%',}" />
            </figure>
          </div>
          <div class="ukm-post--detail">
            <!-- product title -->
            <h2 :style="{fontSize: `${title_size}px`}"><a href="#">{{post.title}}</a></h2>

            <!-- product body -->
            <div :style="{fontSize: `${excerpt_size}px`}" class="excerpt" v-html="getExcerpt(post.body, excerpt_length)"></div>

            <div class="is-flex justify-content-between align-content-center">
              <!-- product price -->
              <div v-if="post.price != 0">
                <div class="price" v-if="post.price_before">
                  <!-- if there is a discount -->
                  <div>
                    <del class="mr1">{{currency}} {{parseInt(post.price_before).toLocaleString()}}</del>
                    <span class="has-text-danger">{{countDiscount(post.price_before, post.price)}}%</span>
                  </div>
                  
                  <ins :style="{fontSize: price_size, color: price_color}">
                    {{currency}} {{parseInt(post.price).toLocaleString()}}
                  </ins>
                </div>

                <div
                  :style="{fontSize: price_size, color: price_color, fontWeight: 'bold',}"
                  class="price" v-else>
                    {{currency}} {{parseInt(post.price).toLocaleString()}}
                </div>
              </div>

              <!-- date -->
              <div class="price">
                {{formatDate(post.created_date)}}
              </div>
            </div>
          </div>
        </div>
      </article>
    </div>
  </div>
  <div v-else class="ukm-posts--empty">
    <h2>Anda belum memiliki katalog</h2>
    <p>Yuk mulai menambahkan katalog untuk produk anda.</p>
    <img :src="`${config.path}/blocks-assets/imgs/site-catalog.jpg`" alt="Site Catalog" class="site-post-instruction" style="box-shadow: 0 10px 30px -5px rgba(0,0,0,.1);margin: 30px 0;">
  </div>

</template>

<script>
export default {
  name: 'c-catalog',
  props: {
    mode : { type: String },
    image_size : { type: String },
    image_corner : { type: String },
    title_size : { type: Number },
    excerpt_size : { type: Number },
    excerpt_length : { type: Number },
    per_page : { type: Number },
    column : { type: Number },
    currency : { type: String },
    price_size : { type: Number },
    price_color : { type: String },
    figure_classname : { type: String },
  },
  computed: {
    posts(){
      return this.$store.state.project.catalogs.slice(0,this.per_page || 3)
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
    },
    countDiscount(priceBefore, priceAfter) {
      const difference = priceBefore - priceAfter;
      const percentage = difference / priceBefore * 100;
      return percentage.toFixed(2);
    }
  }
}
</script>