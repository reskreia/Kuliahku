<template>
  <q-layout ref="layout" view="lHh Lpr lFf">
    <q-toolbar slot="header">
      <q-btn color="primary" flat @click="$refs.layout.toggleLeft()">
        <q-icon name="menu" />
      </q-btn>
      <q-toolbar-title capitalize>
        {{ title }}
      </q-toolbar-title>
    </q-toolbar>
    <!-- Left Side Panel -->
    <q-scroll-area
      slot="left"
      style="height: 100%;"
      class="bg-grey-2"
    >
      <div class="row flex-center" style="width: 100%; height: 100px; background: #1e3c72; background: -webkit-linear-gradient(to right, #2a5298, #1e3c72); background: linear-gradient(to right, #2a5298, #1e3c72);">
       
        <img src="~assets/logo-usm.png" style="height: 75px; width 75px;"/>
        <div style="margin-left: 15px; color:#ffffff;">
          <h5>KULIAHKU</h5>
        </div>
      </div>
      <q-list no-border link >
       <q-list-header>Akademik</q-list-header>
        <q-side-link
          item
          to="/beranda"
          exact>
          <q-item-side icon="dashboard" />
          <q-item-main label="Beranda" />
          <q-item-side right icon="chevron_right" />
        </q-side-link>
        <q-side-link
          item
          to="/pedomankrs/mPedoman"
          exact>
          <q-item-side icon="info" />
          <q-item-main label="Pedoman KRS" />
          <q-item-side right icon="chevron_right" />
        </q-side-link>
        <q-item-separator />
        <q-list-header>Mahasiswa</q-list-header>
        <q-collapsible indent group="mgroup" icon="school" label="Master Data">
        <q-side-link
          item
          to="/master/inputSMS"
          exact>
          <q-item-side icon="layers" />
          <q-item-main label="Semester" />
          <q-item-side right icon="chevron_right" />
        </q-side-link>
         <q-side-link
          item
          to="/master/inputKRS"
          exact>
          <q-item-side icon="local_library" />
          <q-item-main label="KRS" />
          <q-item-side right icon="chevron_right" />
        </q-side-link>
         <q-side-link
          item
          to="/master/inputKHS"
          exact>
          <q-item-side icon="bubble_chart" />
          <q-item-main label="KHS" />
          <q-item-side right icon="chevron_right" />
        </q-side-link>
        </q-collapsible>
        
       <q-collapsible indent group="mgroup" icon="local_library" label="Menu KRS">
        <q-side-link
          item
          to="/mkrs/dashKRS"
          exact>
          <q-item-side icon="ion-ios-speedometer" />
          <q-item-main label="Dasbor" />
          <q-item-side right icon="chevron_right" />
        </q-side-link>
         <q-side-link
          item
          to="/mkrs/cusKRS"
          exact>
          <q-item-side icon="ion-ios-settings-strong" />
          <q-item-main label="Detail" />
          <q-item-side right icon="chevron_right" />
        </q-side-link>
        </q-collapsible>

       <q-collapsible indent group="mgroup" icon="bubble_chart" label="Menu KHS">
        <q-side-link
          item
          to="/mkhs/dashKHS"
          exact>
          <q-item-side icon="ion-ios-speedometer" />
          <q-item-main label="Dasbor" />
          <q-item-side right icon="chevron_right" />
        </q-side-link>
         <q-side-link
          item
          to="/mkhs/updateKHS"
          exact>
          <q-item-side icon="ion-ios-cog" />
          <q-item-main label="Perbarui KHS" />
          <q-item-side right icon="chevron_right" />
        </q-side-link>
        </q-collapsible>
        <q-item-separator />
        <q-list-header>Setting</q-list-header>
        <q-side-link item to="/profile">
          <q-item-side icon="fingerprint" />
          <q-item-main label="Akun" />
        </q-side-link>
        <q-side-link item to="/panduan">
          <q-item-side icon="help_outline" />
          <q-item-main label="Panduan" />
        </q-side-link>
        <q-side-link
          item
          to="/tSRM"
          exact>
          <q-item-side icon="copyright" />
          <q-item-main label="Tentang Kuliahku" />
        </q-side-link>
        <q-item-separator />
        <q-item class="item" v-go-back="'/'" @click="logout()">
          <q-item-side>
            <q-item-tile icon="exit_to_app" />
          </q-item-side>
          <q-item-main>
            <q-item-tile label>Keluar</q-item-tile>
          </q-item-main>
        </q-item>
      </q-list>
    </q-scroll-area>
      
        <router-view />

    </q-layout>
</template>

<style></style>

<script>
  import { GoBack, QCollapsible, QScrollArea, QLayout, QToolbar, QBtn, QIcon, QToolbarTitle, QList, QSideLink, QItemSide, QItemMain, QItem, QItemTile, QListHeader, QItemSeparator } from 'quasar'
  import Auth from '../../auth'

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
    methods: {
      logout () {
        Auth.logout()
      }
    },
    components: { QLayout, QCollapsible, QScrollArea, QToolbar, QBtn, QIcon, QToolbarTitle, QList, QSideLink, QItemSide, QItemMain, QItem, QItemTile, QListHeader, QItemSeparator },
    directives: { GoBack }
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
