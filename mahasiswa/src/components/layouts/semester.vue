<template>
<q-layout ref="layout" view="lHh Lpr lFf" reveal>
    <q-toolbar slot="header">
      <q-btn
        flat
        link
        color="primary"
        v-go-back="'/'"
      >
        <q-icon name="ion-ios-arrow-back" />
      </q-btn>
      <q-toolbar-title>
        {{ title }}
      </q-toolbar-title>
    </q-toolbar>

    <q-tabs slot="navigation" style="background: #f7f7f8" align="justify">
      <q-route-tab
        v-bind:to="{ name: 'inputSMS'}"
        exact
        slot="title"
        icon="layers" color="primary"
      />
      <q-route-tab
        v-bind:to="{ name: 'inputKRS'}"
        exact
        slot="title" 
        icon="local_library" color="primary"
      />
      <q-route-tab
        v-bind:to="{ name: 'inputKHS'}"
        exact
        slot="title"
        icon="bubble_chart" color="primary"
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

