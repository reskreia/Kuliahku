<template>
  <q-layout ref="layout" view="lHh Lpr lFf">
    <q-toolbar slot="header">
        <q-btn
            v-if="canGoBack"
            flat
            link color="primary"
            @click="goBack">
            <q-icon name="ion-ios-arrow-back" />
        </q-btn>
      <q-toolbar-title>
        {{ title }}
      </q-toolbar-title>
    </q-toolbar>

    <router-view />
    
  </q-layout>
</template>

<script>
import {
  QBtn,
  QLayout,
  QToolbar,
  QToolbarTitle,
  QIcon
} from 'quasar'

export default{
  data () {
    return {
      title: '',
      canGoBack: window.history.length > 1
    }
  },
  watch: {
    '$route' () {
      this.title = this.$route.meta.title
    }
  },
  mounted () {
    this.title = this.$route.meta.title
  },
  methods: {
    goBack () {
      window.history.go(-1)
    }
  },
  components: {
    QBtn,
    QLayout,
    QToolbar,
    QToolbarTitle,
    QIcon
  }
}
</script>

<style lang="stylus">
.q-toolbar-title {
  -webkit-box-flex 1
  -ms-flex 1
  flex 1
  min-width 2px
  max-width 100%
  font-size 19px
  font-weight 500
  margin 0px 10px 0px -10px
}
</style>
