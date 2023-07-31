<template>
  <div v-if="posts.length">

    <div class="ukm-posts layout-grid" :data-columns="column">
      <article class="ukm-post--grid" v-for="post in posts" :key="'catalog' + post.id">
        <div class="ukm-post--inner">
          <div :class="`ukm-post--image --size-${image_size || '1by1'}`">
            <figure>
              <!-- product title -->
              <h2 :style="{fontSize: `${title_size}px`}" class="angga-catalog3-product-title">
                <a href="#">{{post.title}}</a>
              </h2>
              
              <!-- product image -->
              <img v-if="post.thumbnail" :src="post.thumbnail.medium" alt="">

              <!-- product price -->
              <div v-if="post.price != 0" class="price angga-catalog3-product-price">
                {{currency}} {{parseInt(post.price).toLocaleString()}}
              </div>
            </figure>
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
    mode           : { type: String },
    image_size     : { type: String },
    title_size     : { type: Number },
    per_page       : { type: Number },
    column         : { type: Number },
    currency       : { type: String }
  },
  computed: {
    posts(){
      return this.$store.state.project.catalogs.slice(0,this.per_page || 3)
    },
    config() {
      return window.builder_vars
    }
  },
}
</script>