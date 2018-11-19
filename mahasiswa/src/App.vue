<template>
  <!-- Don't drop "q-app" class -->
  <div id="q-app">
    <q-transition enter="fadeIn" leave="fadeOut" mode="out-in" :duration="300" @leave="resetScroll">
      <router-view />
    </q-transition>
  </div>
</template>

<script>
import { QTransition } from 'quasar'
export default {
  components: {
    QTransition
  },
  methods: {
    resetScroll (el, done) {
      document.documentElement.scrollTop = 0
      document.body.scrollTop = 0
      done()
    }
  }
}
</script>

<style lang="stylus">
@import '~src/themes/app.ios.styl'
* {
  -webkit-user-select none
  -khtml-user-select none
  -moz-user-select none
  -ms-user-select none
  user-select none
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
.layout-aside-left
  left 0
  width $layout-aside-left-width
  &.fixed
    border-right 0px
.q-toolbar
  .q-btn
    margin 0 0rem
  .q-btn:first-child
      width 24px
.layout-padding
  padding 0.5rem 0.5rem
dl.horizontal dt {
  text-align left
}
dl.horizontal dd {
  margin-left 25%
}
</style>