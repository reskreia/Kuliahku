<template>
  <q-layout ref="layout" view="lHh Lpr lFf">

    <q-toolbar slot="header">
    <q-transition
    appear
    enter="slideInLeft"
    leave="slideOutLeft"
    >
        <q-btn
            flat color="primary"
            v-go-back=" '/'">
            <q-icon name="ion-ios-arrow-back" />
        </q-btn>
        </q-transition>
    <q-transition
    appear
    enter="slideInLeft"
    leave="slideOutLeft"
    >
      <q-toolbar-title>
        MASUK KULIAHKU
      </q-toolbar-title>
   </q-transition>
    </q-toolbar>
    <q-transition
    appear
    enter="slideInLeft"
    leave="slideOutLeft"
    >
    <div class="layout-view layout-padding row page-login">
       <div style="width: 100%">
        <div class="login-top text-center">
          <img src="~assets/logo-usm.png" style="height: 180px; width: 156px;" />
          <h6 class="caption">
            Sistem Monitoring Akademik
            <br>
            Universitas Semarang
          </h6>
        </div>
        <div class="row justify-center">
        <div class="col-10">
        <q-field icon="account_circle">
          <q-input v-model="credentials.login" type="text" placeholder="Nim atau email anda...." class="full-width" />
        </q-field>
        </div>
        <div class="col-10">
        <q-field icon="vpn_key">
          <q-input v-model="credentials.password" type="password" placeholder="Kata sandi...." class="full-width" />
        </q-field>
        </div>
        </div>
    </div>
    </div>
    </q-transition>

      <div class="row justify-center">
        <q-transition
          appear
          enter="slideInLeft"
          leave="slideOutLeft"
        >
          <q-btn class="text-white" style="background: linear-gradient(80deg, #00aeff, #3369e7)" rounded @click="submit()">Masuk</q-btn>
        </q-transition>
      </div>
      <br>

  </q-layout>
  
</template>

<script>
  import { GoBack, QBtn, QLayout, QTransition, QToolbar, QIcon, QToolbarTitle, QField, QInput } from 'quasar'
  import auth from '../../auth'

  export default {
    data () {
      return {
        credentials: {
          login: '',
          password: ''
        }
      }
    },
    methods: {
      submit () {
        auth.login(this.credentials, 'beranda')
      }
    },
    directives: { GoBack },
    components: { QBtn, QLayout, QTransition, QToolbar, QIcon, QToolbarTitle, QField, QInput }
  }
</script>

<style lang="stylus">
.page-login
  .login-top
    margin-top 24px
    margin-bottom 40px
</style>