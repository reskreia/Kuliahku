<template>
  <div class="w-page window-height window-width column items-center no-wrap" style="background: #f7f7f8">
    <div class="banner flex flex-center" style="background: #f7f7f8">
      USM
    </div>
      <q-transition appear
        enter="zoomIn"
        leave="fadeOut">
        <div class="text-center">
          <div class="card bg-white shadow-2 column no-wrap flex-center group">
            <img src="~assets/logo-usm.png" />
            <h6>Sistem Monitoring Akademik</h6>
            <p>Universitas Semarang</p>
            <p></p>
            <q-btn
              rounded class="text-white"
              @click="goTo('login')" style="background: linear-gradient(80deg, #00aeff, #3369e7)">
               Masuk
            </q-btn>
            <p></p>
            <div class="row justify-center">
              <q-btn
                flat rounded color="secondary"
                @click="goTo('Register')"
              >
                Daftar
              </q-btn>
            </div>
          </div>
        </div>
      </q-transition>
      <br>
  </div>
</template>

<script>
  import {
    QBtn,
    QTransition
  } from 'quasar'
  import router from '../../router'

  export default {
    methods: {
      goTo (link) {
        router.push(link)
      }
    },
    components: {
      QBtn,
      QTransition
    }
  }
</script>

<style lang="styl">
.w-page
  .banner
    height 52vh
    width 100%
    font-size 21vmax
    color #0342892b
    overflow hidden
  .card
    width 80vw
    max-width 640px
    padding 8px 5px
    margin-top -95px
    border-radius 2px
    img
      height 139px
      width 118px
  .q-btn {
    padding: 0 32px;
  }
</style>


