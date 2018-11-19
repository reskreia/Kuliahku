<template>
<q-layout ref="layout" view="lHh Lpr lFf" reveal>
    <q-toolbar slot="header">
      <q-btn
        flat
        link color="primary"
        v-go-back="'/'"
      >
        <q-icon name="ion-ios-arrow-back" />
      </q-btn>
      <q-toolbar-title>
        {{ title }}
      </q-toolbar-title>
    </q-toolbar>

    <q-tabs slot="navigation" align="justify">
      <q-route-tab
        v-bind:to="{ name: 'dashKRS'}"
        exact
        slot="title"
        icon="ion-ios-speedometer-outline" color="primary"
      />
      <q-route-tab
        v-bind:to="{ name: 'cusKRS'}"
        exact
        slot="title"
        icon="ion-ios-settings" color="primary"
      />
    </q-tabs>  
     
      <router-view />

</q-layout>
</template>

<script>
  import { GoBack, QLayout, QToolbar, QBtn, QIcon, QToolbarTitle, QTabs, QTab, QRouteTab } from 'quasar'

  export default{
    data () {
      return {
        title: ''
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
    components: { QLayout, QToolbar, QBtn, QIcon, QToolbarTitle, QTabs, QTab, QRouteTab },
    directives: { GoBack }
  }
</script>
