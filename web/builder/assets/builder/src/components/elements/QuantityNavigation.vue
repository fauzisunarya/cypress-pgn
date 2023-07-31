<template>
  <div class="columns is-mobile" style="align-items: center;">
    <!-- prev button -->
    <div class="column is-3 angga-catalog13-quantity-navigation has-text-black">
      <input
        id="angga-catalog13-prev-button"
        type="button"
        v-on:click="changeQuantity('min')"
        style="height: 50px;"
      />
      <span class="angga-catalog13-prev-button-content">
        <i class="fas fa-minus"></i>
      </span>
    </div>

    <!-- quantity input -->
    <div class="column is-3">
      <input
        id="angga-catalog13-input"
        class="angga-catalog13-input"
        type="number"
        v-model="quantity"
      />
    </div>

    <!-- next button -->
    <div class="column is-3 angga-catalog13-quantity-navigation has-text-black">
      <input
        id="angga-catalog13-next-button"
        type="button"
        v-on:click="changeQuantity('plus')"
        style="height: 50px;"
      />
      <span class="angga-catalog13-next-button-content">
        <i class="fas fa-plus"></i>
      </span>
    </div>
  </div>
</template>

<script>
export default {
  name: "QuantityNavigation",
  data() {
    return {
      quantity: 1,
    };
  },
  methods: {
    changeQuantity(param) {
      if (param === "min") {
        if (this.quantity > 1) {
          this.quantity--;
        }
      } else {
        if (this.quantity < 123) {
          this.quantity++;
        }
      }
    },
  },
};
</script>
