import Vue from 'vue'
import VueRouter from 'vue-router'
import auth from 'auth'
import { LocalStorage } from 'quasar'

Vue.use(VueRouter)

function load (component) {
  // '@' is aliased to src/components
  return () => import(`@/${component}.vue`)
}

export default new VueRouter({
  /*
   * NOTE! VueRouter "history" mode DOESN'T works for Cordova builds,
   * it is only to be used only for websites.
   *
   * If you decide to go with "history" mode, please also open /config/index.js
   * and set "build.publicPath" to something other than an empty string.
   * Example: '/' instead of current ''
   *
   * If switching back to default "hash" mode, don't forget to set the
   * build publicPath back to '' so Cordova builds work again.
   */

  mode: 'hash',
  scrollBehavior: () => ({ Y: 0 }),

  routes: [
    { path: '/', component: load('welcome/welcome'), beforeEnter: checkAuth }, // Default
    { path: '/login', component: load('auth/login') },
    { path: '/register', component: load('auth/register') },
    {
      path: '/',
      component: load('layouts/menu'),
      beforeEnter: checkAuth,
      children: [
        { path: 'beranda', name: 'beranda', component: load('mahasiswa/beranda'), meta: { title: 'Beranda' } }
      ]
    },
    {
      path: '/master/',
      component: load('layouts/semester'),
      beforeEnter: checkAuth,
      children: [
        { path: 'inputSMS', name: 'inputSMS', component: load('mahasiswa/masterSemester'), meta: { title: 'Data Semester' } },
        { path: 'inputKRS', name: 'inputKRS', component: load('mahasiswa/masterKrs'), meta: { title: 'Data KRS' } },
        { path: 'inputKHS', name: 'inputKHS', component: load('mahasiswa/masterKhs'), meta: { title: 'Data KHS' } }
      ]
    },
    {
      path: '/mkrs/',
      component: load('layouts/krs'),
      beforeEnter: checkAuth,
      children: [
        { path: 'dashKRS', name: 'dashKRS', component: load('mahasiswa/krs/dashboard'), meta: { title: 'Dashboard KRS' } },
        { path: 'cusKRS', name: 'cusKRS', component: load('mahasiswa/krs/pengaturan'), meta: { title: 'Detail KRS' } }
      ]
    },
    {
      path: '/mkhs/',
      component: load('layouts/khs'),
      beforeEnter: checkAuth,
      children: [
        { path: 'dashKHS', name: 'dashKHS', component: load('mahasiswa/khs/dashboard'), meta: { title: 'Dashboard KHS' } },
        { path: 'updateKHS', name: 'updateKHS', component: load('mahasiswa/khs/pembaruan'), meta: { title: 'Transkripsi Nilai' } }
      ]
    },
    {
      path: '/pedomankrs/',
      component: load('layouts/pedoman'),
      beforeEnter: checkAuth,
      children: [
        { path: 'mPedoman', name: 'mPedoman', component: load('pedoman/pedoman'), meta: { title: 'Pedoman KRS' } },
        { path: 'mRekomen', name: 'mRekomen', component: load('pedoman/rekomendasi'), meta: { title: 'Rekomendasi KRS' } }
      ]
    },
    {
      path: '/',
      component: load('layouts/mBack'),
      beforeEnter: checkAuth,
      children: [
        { path: 'tSRM', name: 'tSRM', component: load('about/aboutSRM'), meta: { title: 'Tentang Kuliahku' } },
        { path: 'profile', name: 'pAkun', component: load('profile/profile'), meta: { title: 'Akun Saya' } },
        { path: 'panduan', name: 'apanduan', component: load('panduan/panduan'), meta: { title: 'Panduan' } }
      ]
    },
    // Always leave this last one
    { path: '*', component: load('Error404') } // Not found
  ]
})

function checkAuth (to, from, next) {
  if (to.path === '/' && auth.user.authenticated) {
    next('/beranda')
  }
  else if (!LocalStorage.get.item('id_token') && to.path !== '/') {
    console.log('not logged')
    next('/login')
  }
  else {
    next()
  }
}
