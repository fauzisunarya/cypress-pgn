<template>
  <div>
    <!-- 
      Empty field: props data can't be manipulate
      Use this field if you need to set some value, but the value is hidden & can not be changed

      example use case:

      in pricing table, we need to render packages from database. Use the field type "repeatable" to loop the package.
      in each package, data like title, subtitle, etc can be customized (using field type like "text","select", or etc).
      but, in each package we need identifier (package_id), we need field type "empty" to store the value.

      so, in url landing page, the field value can be obtained
     -->
  </div>
</template>

<script>
export default {
  name: 'field-empty',
  props: {
    data: Object
  }
}
</script>