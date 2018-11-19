import Vue from 'vue'
import VueRouter from 'vue-router'
import auth from 'auth'
import { LocalStorage } from 'quasar'

Vue.use(VueRouter)

function load (component) {
  // '@' is aliased to src/components
  return () => import(`@/${component}.vue`)
}

/*
 * Uncomment this section and use "load()" if you want
 * to lazy load routes.
function load (component) {
  // '@' is aliased to src/components
  return () => import(`@/${component}.vue`)
}
*/

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
  routes: [
    { path: '/login', component: load('auth/login') },
    {
      path: '/',
      component: load('layouts/menu'),
      beforeEnter: checkAuth,
      children: [
        { path: 'beranda', name: 'beranda', component: load('beranda/beranda'), meta: { title: 'Sistem Monitoring Akademik' } },
        { path: 'aFakultas', name: 'aFakultas', component: load('fakultas/fakultas'), meta: { title: 'Master Fakultas' } },
        { path: 'aJurusan', name: 'aJurusan', component: load('jurusan/jurusan'), meta: { title: 'Master Program Studi' } },
        { path: 'aProgdi', name: 'aProgdi', component: load('progdi/progdi'), meta: { title: 'Menu Program Studi' } },
        { path: 'aMakul', name: 'aMakul', component: load('makul/mataKuliah'), meta: { title: 'Menu Mata Kuliah' } },
        { path: 'aDosen', name: 'aDosen', component: load('dosen/dosen'), meta: { title: 'Menu Dosen' } },
        { path: 'daftarMahasiswa', name: 'daftarMahasiswa', component: load('akademik/akademik'), meta: { title: 'Menu Akademik' } },
        { path: 'aMahasiswa', name: 'aMahasiswa', component: load('mahasiswa/mahasiswa'), meta: { title: 'Menu Mahasiswa' } }
      ]
    },
    {
      path: '/adetail/mahasiswa/:nim/',
      component: load('layouts/mahasiswa'),
      beforeEnter: checkAuth,
      children: [
        { path: 'biodata', name: 'mBiodata', component: load('mahasiswa/biodata'), meta: { title: 'Detail Mahasiswa' } },
        { path: 'semester', name: 'mSemester', component: load('mahasiswa/semester'), meta: { title: 'Detail Mahasiswa' } },
        { path: 'krs', name: 'mKrs', component: load('mahasiswa/krs'), meta: { title: 'Detail Mahasiswa' } }
      ]
    },
    { path: 'adetail/mahasiswa/:nim', beforeEnter: checkAuth, name: 'detailMahasiswa', component: load('mahasiswa/detail'), meta: { title: 'Detail Mahasiswa' } },
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
