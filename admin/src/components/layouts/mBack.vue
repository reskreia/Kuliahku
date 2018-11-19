<template>
  <q-layout ref="layout" view="lHh Lpr lFf" reveal>
    <q-toolbar slot="header" style="background: #034289">
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
