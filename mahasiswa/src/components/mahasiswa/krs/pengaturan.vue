<template>
  <div class="layout-padding pmakul row justify-center">
    <div style="width: 100%; max-width: 100vw;">

      <q-list>
        <q-list-header>Informasi KRS</q-list-header>
        <q-item>
          <q-item-main>
            <q-item-tile label>Makul Wajib</q-item-tile>
            <q-item-tile sublabel>
                  <strong>{{ amakul }}</strong> makul wajib, total sks <strong>{{ asks }}</strong>.<br>
                    Makul yang diambil:<br>
                    <strong>{{ mambil }}</strong> 
                    dengan total sks <strong>{{ msks }}</strong>.
            </q-item-tile>
          </q-item-main>
        </q-item>
        <q-item>
          <q-item-main>
            <q-item-tile sublabel>
              Jika anda sudah mengambil semua makul wajib, anda bisa mengambil makul pilihan untuk menambah jumlah sks.
            </q-item-tile>
          </q-item-main>
        </q-item>
      </q-list>

      <q-list>
        <q-list-header>Informasi Makul</q-list-header>
        <q-item>
          <q-item-main>
            <q-item-tile label>Cari Makul</q-item-tile>
            <q-item-tile sublabel>Mencari makul yang belum diambil.</q-item-tile>
          </q-item-main>
        </q-item>
        <q-item tag="label">
          <q-item-main>
            <q-select class="full-width" radio inverted color="blue-grey" v-model="cmakul.dipilih" :options="statKrs" />
          </q-item-main>
        </q-item>
        <q-item>
          <q-item-main>
            <q-item-side>
          <q-btn loader class="full-width" color="primary" @click="submitCari">
            Cari
            <span slot="loading">
              <q-spinner-puff class="on-left" />
              Mencari...
            </span>
          </q-btn>
         </q-item-side>
          </q-item-main>
        </q-item>
        <q-item-separator />
        <q-item>
          <q-item-main>
            <q-item-tile label>Makul Mengulang</q-item-tile>
          </q-item-main>
        </q-item>
        <q-item>
          <q-item-main>
            <q-item-tile sublabel>Mencari makul yang diulang.</q-item-tile>
            <br>
           <q-item-tile sublabel>
          <q-btn loader class="full-width" color="secondary" @click="submitUlang">
            Cari
            <span slot="loading">
              <q-spinner-puff class="on-left" />
              Mencari...
            </span>
          </q-btn>
         </q-item-tile>

          </q-item-main>
        </q-item>
      </q-list>
    </div>

    <q-modal v-model="modalMakul" :content-css="{minWidth: '80vw', minHeight: '80vh'}">
      <q-modal-layout>
        <q-toolbar slot="header">
          <q-btn color="primary" flat @click="tutupModal">
            <q-icon name="ion-ios-arrow-back" />
          </q-btn>
          <q-toolbar-title>
            Daftar Makul
          </q-toolbar-title>
        </q-toolbar>

        <q-toolbar slot="header">
          <q-search inverted icon="ion-ios-search" v-model="wfMakul" color="blue-10" placeholder="Cari nama makul..." />
        </q-toolbar>
        <div class="layout-padding">
            <q-list>
               <q-list-header>Total makul : <strong>{{ wmakul.length }}</strong></q-list-header>
                <q-item inset-separator v-for="(d, index) in filterWajib" :key="index">
                  <q-item-side color="blue-10"> <big>{{index + 1}}</big> </q-item-side>
                  <q-item-main>
                    <q-item-tile label>{{ d.label }}</q-item-tile>
                    <q-item-tile sublabel>Semester {{ d.sms }}</q-item-tile>
                  </q-item-main>
                  <q-item-side right>{{ d.sks }} sks
                  </q-item-side>
                </q-item>
            </q-list>  
        </div>
      </q-modal-layout>
    </q-modal>

    <q-modal v-model="mUlang" :content-css="{minWidth: '80vw', minHeight: '80vh'}">
      <q-modal-layout>
        <q-toolbar slot="header">
          <q-btn color="primary" flat @click="tutupUlang">
            <q-icon name="ion-ios-arrow-back" />
          </q-btn>
          <q-toolbar-title>
            Makul Mengulang
          </q-toolbar-title>
        </q-toolbar>

        <q-toolbar slot="header">
          <q-search inverted icon="ion-ios-search" v-model="ufMakul" color="blue-10" placeholder="Cari nama makul..."></q-search>
        </q-toolbar>
        <div class="layout-padding">
            <q-list>
               <q-list-header>Total makul : <strong>{{ umakul.length }}</strong></q-list-header>
                <q-item inset-separator v-for="(u, index) in filterUlang" :key="index">
                  <q-item-side color="blue-10"> <big>{{index + 1}}</big> </q-item-side>
                  <q-item-main :label="u.sms" :label-lines="1" :sublabel="u.label" :sublabel-lines="2" />
                  <q-item-side right>{{ u.sks }} sks
                  </q-item-side>
                </q-item>
            </q-list>   
        </div>
      </q-modal-layout>
    </q-modal>
  </div>
</template>

<script>
import {
  QList,
  QListHeader,
  QItem,
  QItemSeparator,
  QItemSide,
  QItemMain,
  QItemTile,
  QBtn,
  QIcon,
  QSpinnerPuff,
  QSelect,
  QModal,
  QModalLayout,
  QToolbar,
  QToolbarTitle,
  QSearch,
  Toast
} from 'quasar'
import axios from 'axios'

export default {
  components: {
    QList,
    QBtn,
    QModal,
    QListHeader,
    QItem,
    QItemSeparator,
    QItemSide,
    QItemMain,
    QItemTile,
    QIcon,
    QSpinnerPuff,
    QModalLayout,
    QToolbar,
    QToolbarTitle,
    QSearch,
    QSelect
  },
  data () {
    return {
      statKrs: [
        {
          label: 'Wajib',
          inset: true,
          value: '1'
        },
        {
          label: 'Pilihan',
          inset: true,
          value: '2'
        }
      ],
      cmakul: {
        dipilih: '1'
      },
      wmakul: [],
      umakul: [],
      wfMakul: '',
      ufMakul: '',
      modalMakul: false,
      mUlang: false,
      amakul: 0,
      asks: 0,
      msks: 0,
      mambil: 0
    }
  },
  mounted () {
    this.ambilKRS()
  },
  computed: {
    filterWajib () {
      return this.wmakul.filter(d => {
        return d.label.toLowerCase().indexOf(this.wfMakul.toLowerCase()) > -1
      })
    },
    filterUlang () {
      return this.umakul.filter(u => {
        return u.label.toLowerCase().indexOf(this.ufMakul.toLowerCase()) > -1
      })
    }
  },
  methods: {
    ambilKRS () {
      axios.get('dKrs').then((response) => {
        this.amakul = response.data.amakul
        this.asks = response.data.asks
        this.msks = response.data.msks
        this.mambil = response.data.mambil
      })
    },
    submitCari (e, done) {
      const vm = this
      axios.post('cKrs', vm.cmakul).then((response) => {
        setTimeout(() => {
          vm.wmakul = response.data
          done()
          vm.modalMakul = true
        }, 1500)
      }).catch((error) => {
        setTimeout(() => {
          done()
          Toast.create.info(error.response.data.message)
        }, 1500)
      })
    },
    submitUlang (e, done) {
      const vm = this
      axios.get('mul').then((response) => {
        setTimeout(() => {
          vm.umakul = response.data
          done()
          vm.mUlang = true
        }, 1500)
      }).catch((error) => {
        setTimeout(() => {
          done()
          Toast.create.info(error.response.data.message)
        }, 1500)
      })
    },
    tutupUlang () {
      this.mUlang = false
      this.umakul = []
      this.utmakul = 0
    },
    tutupModal () {
      this.modalMakul = false
      this.wmakul = []
      this.tmakul = 0
    }
  }
}
</script>

<style lang="stylus">
.pmakul
  .q-list
    & + .q-list
      margin-top 10px
</style>