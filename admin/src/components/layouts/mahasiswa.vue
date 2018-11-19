<template>
<q-layout ref="layout" view="hHh Lpr lfr" reveal>
    <q-toolbar slot="header" style="background: #034289">
      <q-btn
        v-if="canGoBack"
        flat
        link
        v-go-back="'/aMahasiswa'"
      >
        <q-icon name="arrow_back" />
      </q-btn>
      <q-toolbar-title>
        {{ title }}
      </q-toolbar-title>
    </q-toolbar>
    
    <q-tabs slot="navigation" align="justify" color="white" >
      <q-route-tab
        v-bind:to="{ name: 'mBiodata'}"
        exact
        slot="title" label="Biodata"
        color="blue-10"
      />
      <q-route-tab
        v-bind:to="{ name: 'mSemester'}"
        exact
        slot="title" label="Semester"
        color="blue-10"
      />
      <q-route-tab
        v-bind:to="{ name: 'mKrs'}"
        exact
        slot="title" label="KHS"
        color="blue-10"
      />
    </q-tabs> 

  <router-view />

  <q-toolbar slot="footer" style="background: #034289">
    <q-toolbar-title>
      Kuliahku © 2018
    </q-toolbar-title>
  </q-toolbar>

</q-layout>
</template>

<script>
  import {
    GoBack,
    QLayout,
    QToolbar,
    QBtn,
    QIcon,
    QToolbarTitle,
    QTabs,
    QTab,
    QRouteTab,
    QList,
    QListHeader,
    QItem,
    QItemSeparator,
    QItemSide,
    QItemMain,
    QItemTile
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

    components: {
      QLayout,
      QToolbar,
      QBtn,
      QIcon,
      QToolbarTitle,
      QTabs,
      QTab,
      QRouteTab,
      QList,
      QListHeader,
      QItem,
      QItemSeparator,
      QItemSide,
      QItemMain,
      QItemTile
    },
    directives: { GoBack }
  }
</script>
