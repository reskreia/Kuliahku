<template>
  <!-- Don't drop "q-app" class -->
  <div id="q-app">
    <header></header>
    <main>

      <router-view></router-view>
    </main>
  </div>
</template>

<script>
/*
 * Root component
 */
export default {}
</script>

<style lang="stylus">
@import '~variables'
* {
  -webkit-touch-callout none
  -moz-touch-callout none
  -ms-touch-callout none
  touch-callout none
  -webkit-user-drag none
  -moz-user-drag none
  -ms-user-drag none
  user-drag none
}
input {
  -webkit-user-select auto !important
  -khtml-user-select auto !important
  -moz-user-select auto !important
  -ms-user-select auto !important
  user-select auto !important
}
</style>
