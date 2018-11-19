import Router from './router'
import { Toast, LocalStorage, Loading, QSpinnerDots } from 'quasar'
import axios from 'axios'

const LOGIN_URL = 'mimin/login'
const USER_URL = 'admin/auth'
const REFRESH_TOKEN = 'mimin/refresh-token'

export default {

  user: {
    authenticated: false
  },

  login (creds, redirect) {
    axios.post(LOGIN_URL, creds)
      .then((response) => {
        LocalStorage.set('id_token', response.data.token)

        this.user.authenticated = true
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + LocalStorage.get.item('id_token')
        this.getAuthUser()

        if (redirect) {
          setTimeout(() => Router.replace(redirect), 700)
        }
      })
      .catch((error) => {
        Toast.create.negative(error.response.data.message)
      })
  },

  logout () {
    LocalStorage.clear()
    this.user.authenticated = false
    Router.replace('/login')
    Toast.create.positive('Anda keluar dari aplikasi.')
  },

  checkAuth () {
    let jwt = LocalStorage.get.item('id_token')

    if (jwt) {
      this.user.authenticated = true
      axios.defaults.headers.common['Authorization'] = 'Bearer ' + LocalStorage.get.item('id_token')
      this.refreshToken()
    }
    else {
      this.user.authenticated = false
    }
  },

  refreshToken () {
    var that = this

    axios.post(REFRESH_TOKEN)
      .then((response) => {
        // Store refreshed token
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.token
        LocalStorage.set('id_token', response.data.token)
        that.getAuthUser()
      }, () => {
        Toast.create.negative('Oups! Terjadi kesalahan.')
        that.logout()
      })
  },

  getAuthUser () {
    axios.get(USER_URL)
      .then((response) => {
        LocalStorage.set('user', response.data)
      }, () => {
        Toast.create.negative('Oups! Terjadi kesalahan.')
      })
  },

  showLoading () {
    Loading.show({
      spinner: QSpinnerDots,
      spinnerSize: 56,
      spinnerColor: 'primary',
      message: 'Koneksi dengan server terputus.\n Menghubungkan....',
      messageColor: 'white'
    })
    setTimeout(() => {
      Loading.hide()
    }, 2000)
  }
}
