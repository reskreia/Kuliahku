<template>
  <div class="detailSemester">
  <div class="layout-padding row justify-center">
    <div style="width: 100%">
      <div v-show="oke" class="row sm-gutter">
        <div class="col-xs-12 col-md-6">
          <div class="row sm-gutter">
            <div class="col-xs-12 col-md-12">
              <q-list>
                <q-list-header>KRS SEMESTER</q-list-header>
                <q-item>
                  <q-item-main>
                    <q-item-tile sublabel>
                      <krs-chart :height="150" />
                    </q-item-tile>
                  </q-item-main>
                </q-item>
              </q-list>
            </div>
            <div class="col-xs-12 col-md-12">
              <q-list inset-separator v-for="(ds, index) in dataSms" :key="index">
                <q-list-header><span class="text-bold text-teal-7">{{ ds.semester }}</span></q-list-header>
                  <q-item v-for="(m, index) in ds.makul" :key="index">
                    <q-item-side color="blue-grey"><big>{{index + 1}}</big></q-item-side>
                      <q-item-main>
                        <q-item-tile color="black" sublabel>{{ m.kode_makul }} - {{ m.nama_makul }}</q-item-tile>
                      </q-item-main>
                    <q-item-tile stamp>
                      <div v-if="m.status_makul == 'Baru'">
                        <span class="text-bold text-blue-9">{{ m.status_makul }}</span>&emsp;{{ m.sks }}
                      </div>
                      <div v-if="m.status_makul == 'Mengulang'">
                        <span class="text-bold text-red-7">{{ m.status_makul }}</span>&emsp;{{ m.sks }}
                      </div>
                    </q-item-tile>
                  </q-item>
                  <q-list-header>
                    <div v-if="ds.keterangan === null">
                      
                    </div>
                    <div v-else>
                      <span class="text-bold">keterangan: {{ ds.keterangan }}</span>
                    </div>
                  </q-list-header>
              </q-list>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-md-6">
          <div class="row sm-gutter">
            <div class="col-xs-12 col-md-12">
              <q-list inset-separator >
                <q-list-header>INFORMASI SEMESTER</q-list-header>
                  <q-item v-for="(dm, index) in dmSms" :key="index">
                    <q-item-side color="blue-grey"><big>{{index + 1}}</big></q-item-side>
                      <q-item-main>
                        <q-item-tile label>{{dm.nama}}</q-item-tile>
                        <q-item-tile sublabel>{{ dm.ket }}</q-item-tile>
                      </q-item-main>
                  </q-item>
              </q-list>
            </div> 
            <div class="col-xs-12 col-md-12">
              <q-list>
                <q-list-header>INFORMASI KRS</q-list-header>
                <q-item>
                  <q-item-main>
                    <q-item-tile label>Makul Wajib</q-item-tile>
                    <q-item-tile sublabel>
                      <strong>{{ totalmakul }}</strong> makul wajib, total sks <strong>{{ totalsks }}</strong>.<br>
                      Makul yang diambil:<br>
                      <strong>{{ makuldiambil }}</strong> 
                      dengan total sks <strong>{{ totalsksdiambil }}</strong>.
                    </q-item-tile>
                  </q-item-main>
                </q-item>
                <q-item>
                  <q-item-main>
                    <q-item-tile label>Makul Pilihan</q-item-tile>
                    <q-item-tile sublabel>
                      <strong>{{ totalmakulpilihan }}</strong> makul pilihan, total sks <strong>{{ totalskspilihan }}</strong>.<br>
                      Makul yang diambil:<br>
                      <strong>{{ pilihandiambil }}</strong> 
                      dengan total sks <strong>{{ totalskspilihandiambil }}</strong>.
                    </q-item-tile>
                  </q-item-main>
                </q-item>
                <q-item>
                  <q-item-main>
                    <q-item-tile label>Total sks diperoleh:<span class="text-blue-10 text-bold"> {{ totalsksdiambil + totalskspilihandiambil }}</span></q-item-tile>
                  </q-item-main>
                </q-item>             
              </q-list>
            </div>
            <div class="col-xs-12 col-md-12">
              <q-list inset-separator >
                <q-list-header>MAKUL BELUM DIAMBIL</q-list-header>
                  <q-item v-for="(b, index) in krsBelum" :key="index">
                    <q-item-side color="blue-grey"><big>{{index + 1}}</big></q-item-side>
                      <q-item-main>
                        <q-item-tile color="black" sublabel>{{ b.sublabel }} - {{ b.label }}</q-item-tile>
                      </q-item-main>
                    <q-item-tile stamp>
                      <div v-if="b.status == 'Wajib'">
                        <span class="text-indigo">{{ b.status }}</span>&emsp;{{ b.sks }}
                      </div>
                      <div v-if="b.status == 'Pilihan'">
                        <span class="text-purple-9">{{ b.status }}</span>&emsp;{{ b.sks }}
                      </div>                  

                    </q-item-tile>
                  </q-item>
              </q-list>
            </div> 
          </div>
        </div>
      </div>
      <q-inner-loading :visible="visible">
         <q-spinner-gears size="50px" color="primary"></q-spinner-gears>
      </q-inner-loading> 
    </div>
  </div>
  </div>
</template>

<script>
import {
  QBtn,
  QIcon,
  QInnerLoading,
  QSpinnerGears,
  QList,
  QListHeader,
  QItem,
  QItemSeparator,
  QItemSide,
  QItemMain,
  QItemTile
} from 'quasar'
import axios from 'axios'
import KrsChart from './KrsChart.js'

export default {
  components: {
    'krs-chart': KrsChart,
    QBtn,
    QIcon,
    QInnerLoading,
    QSpinnerGears,
    QList,
    QListHeader,
    QItem,
    QItemSeparator,
    QItemSide,
    QItemMain,
    QItemTile
  },
  data () {
    return {
      totalmakul: 0,
      totalsks: 0,
      totalsksdiambil: 0,
      makuldiambil: 0,
      totalmakulpilihan: 0,
      totalskspilihan: 0,
      totalskspilihandiambil: 0,
      pilihandiambil: 0,
      dataSms: [],
      krsBelum: [],
      dmSms: [],
      visible: true,
      oke: false
    }
  },
  mounted () {
    this.ambilKrs()
    this.krsDetail()
  },
  methods: {
    ambilKrs () {
      const vm = this
      setTimeout(() => {
        axios.get('adminLihatSms/' + vm.$route.params.nim).then((response) => {
          vm.dataSms = response.data
          vm.oke = true
          vm.visible = false
        })
      }, 2000)
      axios.get('adminLihatKrsBelum/' + vm.$route.params.nim).then((response) => {
        vm.krsBelum = response.data
      })
    },
    krsDetail () {
      const vm = this
      axios.get('adminDetailKrs/' + vm.$route.params.nim).then((response) => {
        vm.totalmakul = response.data.amakul
        vm.totalsks = response.data.asks
        vm.totalsksdiambil = response.data.msks
        vm.makuldiambil = response.data.mambil

        vm.totalmakulpilihan = response.data.makulpilih
        vm.totalskspilihan = response.data.totalskspilih
        vm.totalskspilihandiambil = response.data.skspilihan
        vm.pilihandiambil = response.data.pilihandiambil
      })
      axios.get('adminListDetailSms/' + vm.$route.params.nim).then((response) => {
        vm.dmSms = response.data
      })
    }
  }
}
</script>

<style lang="stylus">
.detailSemester
  @media (min-width: 800px) {
    .layout-padding {
      padding 2rem 2rem
    }
    .q-list + .q-list {
      margin-top: 14px;
    }
  }
</style>
