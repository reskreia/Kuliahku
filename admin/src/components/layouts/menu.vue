<template>
  <q-layout ref="layout" view="lHh Lpr lfr">
    <q-toolbar slot="header" style="background: #034289">
      <q-btn flat @click="$refs.layout.toggleLeft()">
        <q-icon name="menu" />
      </q-btn>
      <q-toolbar-title>
        {{ title }}
      </q-toolbar-title>
    </q-toolbar>
    <!-- Left Side Panel -->
    <q-scroll-area
      slot="left"
      style="height: 100%;"
      class="bg-grey-1"
    >
      <div class="row flex-center" style="width: 100%; height: 100px; background: #1e3c72; background: -webkit-linear-gradient(to right, #2a5298, #1e3c72); background: linear-gradient(to right, #2a5298, #1e3c72);">
       
        <img src="~assets/logo-usm.png" style="height: 75px; width 75px;"/>
        <div style="margin-left: 15px; color:#ffffff;">
          <h5>USM JAYA</h5>
        </div>
      </div>
      <q-list no-border link >
        <q-side-link
          item
          to="/beranda"
          exact>
          <q-item-side icon="dashboard" />
          <q-item-main label="Beranda" />
          <q-item-side right icon="chevron_right" />
        </q-side-link>

        <q-list-header>Akademik</q-list-header>

       <q-collapsible indent icon="local_library" label="Master Data">
        <q-side-link
          item
          to="/aFakultas"
          exact>
          <q-item-side icon="label" />
          <q-item-main label="Fakultas" />
          <q-item-side right icon="chevron_right" />
        </q-side-link>
        <q-side-link
          item
          to="/aJurusan"
          exact>
          <q-item-side icon="label" />
          <q-item-main label="Program Studi" />
          <q-item-side right icon="chevron_right" />
        </q-side-link>
        <q-side-link
          item
          to="/aMakul"
          exact>
          <q-item-side icon="label" />
          <q-item-main label="Mata Kuliah" />
          <q-item-side right icon="chevron_right" />
        </q-side-link>
        <q-side-link
          item
          to="/daftarMahasiswa"
          exact>
          <q-item-side icon="label" />
          <q-item-main label="Daftar Nim" />
          <q-item-side right icon="chevron_right" />
        </q-side-link>
        </q-collapsible>
        <q-side-link
          item
          to="/aProgdi"
          exact>
          <q-item-side icon="chrome_reader_mode" />
          <q-item-main label="Kurikulum" />
          <q-item-side right icon="chevron_right" />
        </q-side-link> 
        <q-list-header>User</q-list-header>
        <q-side-link
          item
          to="/aDosen"
          exact>
          <q-item-side icon="person" />
          <q-item-main label="Dosen" />
          <q-item-side right icon="chevron_right" />
        </q-side-link>
         <q-side-link
          item
          to="/aMahasiswa"
          exact>
          <q-item-side icon="supervisor_account" />
          <q-item-main label="Mahasiswa" />
          <q-item-side right icon="chevron_right" />
        </q-side-link>

        <q-item-separator />
        <q-item class="item" v-go-back="'/'" @click="logout()">
          <q-item-side >
            <q-item-tile icon="exit_to_app" />
          </q-item-side>
          <q-item-main>
            <q-item-tile label>Keluar</q-item-tile>
          </q-item-main>
        </q-item>
      </q-list>
    </q-scroll-area>

      <router-view />

        <q-toolbar slot="footer" style="background: #034289">
          <q-toolbar-title>
            Kuliahku © 2018
          </q-toolbar-title>
        </q-toolbar>

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
