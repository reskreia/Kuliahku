<template>
  <q-layout ref="layout" view="lHh Lpr lFf">
    <q-toolbar slot="header">
      <q-transition
      appear
      enter="slideInLeft"
      leave="slideOutLeft"
      >
      <q-btn flat v-go-back="'/'">
        <q-icon color="primary"name="ion-ios-arrow-back" />
      </q-btn>
      </q-transition>
      <q-transition
      appear
      enter="slideInLeft"
      leave="slideOutLeft"
      >
      <q-toolbar-title>
        DAFTAR KULIAHKU
      </q-toolbar-title>
      </q-transition>
    </q-toolbar>

    <q-transition
    appear
    enter="slideInLeft"
    leave="slideOutLeft"
    >
    <div class="layout-padding page-register">
        <div class="row justify-center register-top text-center">
          <img src="~assets/logo-usm.png" style="height: 180px; width: 156px;" />
          </div>
          <div class="row justify-center register-top text-center">
          <h6 class="caption">
            Sistem Monitoring Akademik
            <br>
            Universitas Semarang
          </h6>
        </div>
        <div v-show="buttonCek" class="row justify-center">
          <div class="col-10">
            <q-field icon="verified_user">
              <q-input v-model="nimnya" placeholder="G.xxx.xx.xxx" class="full-width" />
            </q-field>
            <br>
          </div>
          <div class="col-10">
            <div class="row justify-center">
              <q-btn class="text-white" style="background: linear-gradient(80deg, #00aeff, #3369e7)" loader rounded @click="cekNim">Cek NIM</q-btn>
            </div>
          </div>
        </div>
        <div v-show="nimAda" class="row justify-center">
          <div class="col-10">
            <q-field icon="verified_user">
              <q-input readonly v-model="credentials.nim" float-label="NIM" class="full-width" />
            </q-field>
          </div>
          <div class="col-10">
            <q-field icon="person">
              <q-input v-model="credentials.name" float-label="Nama Lengkap" class="full-width" />
            </q-field>
          </div>
          <div class="col-10">
            <q-field icon="mail">
              <q-input v-model="credentials.email" float-label="Email" class="full-width" />
            </q-field>
          </div>
          <div class="col-10">
            <q-field icon="vpn_key">
              <q-input v-model="credentials.password" type="password" float-label="Kata Sandi" class="full-width" />
            </q-field>
          </div>
          <div class="col-10">
            <q-field icon="vpn_key">
              <q-select radio separator float-label="Fakultas"
                v-model="credentials.fakultas"
                :options="listFk"
              />
            </q-field>
          </div>
          <div class="col-10">
            <q-field icon="vpn_key">
              <q-select radio separator float-label="Program Studi"
                v-model="credentials.progdi"
                :options="listProg"
              />
            </q-field>
          </div>
          <div class="col-10">
            <q-field icon="vpn_key">
              <q-select radio filter separator float-label="Dosen Wali"
                v-model="credentials.wali_id"
                :options="waliDosen"
              />
            </q-field>
          </div>
          <div class="col-10">
            <br>
            <div class="row items-center">
            <q-checkbox color="light-blue" v-model="disBtn" label="Anda yakin jika data sudah benar semua?" />
            </div>
          </div>
          <div class="col-10">
            <br>
            <div class="row justify-center">
              <q-btn class="text-white" :disabled="disBtn == false" style="background: linear-gradient(80deg, #00aeff, #3369e7)" rounded @click="submit()">Daftar</q-btn>
            </div>
          </div>
        </div>
    </div>
    </q-transition>
    {{ progdi }}
    {{ wali }}
    <div><br></div>
  </q-layout>
</template>

<script>
  import { GoBack, QSelect, QCheckbox, QLayout, QBtn, QToolbar, QAlert, QTransition, QIcon, QToolbarTitle, QField, QInput, Toast } from 'quasar'
  import auth from '../../auth'
  import axios from 'axios'

  export default {
    data () {
      return {
        nimAda: false,
        buttonCek: true,
        nimnya: '',
        disBtn: false,
        listFk: [],
        listProg: [],
        waliDosen: [],
        credentials: {
          nim: '',
          name: '',
          email: '',
          password: '',
          fakultas: '',
          progdi: '',
          wali_id: ''
        }
      }
    },
    computed: {
      progdi () {
        axios.get('mDaftarProgdi/' + this.credentials.fakultas).then((response) => {
          this.listProg = response.data
        })
      },
      wali () {
        axios.get('mDaftarWali/' + this.credentials.fakultas).then((response) => {
          this.waliDosen = response.data
        })
      }
    },
    methods: {
      submit () {
        auth.signup(this.credentials, 'beranda')
      },
      ambilFakultas () {
        axios.get('mDaftarFakultas').then((response) => {
          this.listFk = response.data
        })
      },
      cekNim (e, done) {
        axios.get('cekNim/' + this.nimnya).then((response) => {
          setTimeout(() => {
            done()
            this.credentials.nim = response.data.nim
            this.nimAda = true
            this.buttonCek = false
            this.ambilFakultas()
          }, 1500)
        }).catch((error) => {
          setTimeout(() => {
            done()
            Toast.create.negative(error.response.data.message)
          }, 1500)
        })
      }
    },
    components: { QBtn, QSelect, QCheckbox, QLayout, QToolbar, QAlert, QTransition, QIcon, QToolbarTitle, QField, QInput },
    directives: { GoBack }
  }
</script>

<style lang="stylus">
.page-register
  .register-top
    margin-bottom 5px
</style>
