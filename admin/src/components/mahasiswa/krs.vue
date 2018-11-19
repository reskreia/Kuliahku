<template>
  <div class="detailMahasiswa">
  <div class="layout-padding">
    <div style="width: 100%">
      <div class="row sm-gutter">
        <div class="col-xs-12 col-md-6">
          <div class="row sm-gutter">

            <div class="col-xs-12 col-md-12">
              <q-list>
                <q-list-header>PEROLEHAN NILAI</q-list-header>
                <q-item>
                  <q-item-main>
                    <q-item-tile sublabel>
                      <nilai-chart :height="240" />
                    </q-item-tile>
                  </q-item-main>
                </q-item>
              </q-list>
            </div>

            <div class="col-xs-12 col-md-12">
              <q-list>
                <q-list-header>IPK SEMESTER</q-list-header>
                <q-item>
                  <q-item-main>
                    <q-item-tile sublabel>
                      <khs-chart :height="110" />
                    </q-item-tile>
                  </q-item-main>
                </q-item>
              </q-list>
            </div>

          </div>
        </div>

        <div class="col-xs-12 col-md-6">
          <q-list dense inset-separator v-for="(dk, index) in dataKrs" :key="index">
            <q-list-header>{{ dk.kategori }}</q-list-header>
            <q-item v-for="(m, index) in dk.makul" :key="index">
              <q-item-side color="blue-10"> <big>{{index + 1}}</big> </q-item-side>
              <q-item-main>
                <q-item-tile label>{{ m.namaMk }}</q-item-tile>
                <q-item-tile sublabel>
                  <div v-if="m.huruf === 'K' || m.huruf === 'T' || m.huruf === 'E' || m.huruf === 'D'">
                    Nilai: <span class="text-bold text-red">{{ m.huruf }}</span>
                  </div>   
                  <div v-else-if="m.huruf === 'C'">
                    Nilai: <span class="text-bold text-purple">{{ m.huruf }}</span>
                  </div>       
                  <div v-else>
                    Nilai: <span class="text-bold text-primary">{{ m.huruf }}</span>
                  </div>
                </q-item-tile>
              </q-item-main>
              <q-item-tile stamp>{{ m.sks }}</q-item-tile>
            </q-item>
          </q-list>
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
import KhsChart from './KhsChart.js'
import NilaiChart from './NilaiChart.js'

export default {
  components: {
    'khs-chart': KhsChart,
    'nilai-chart': NilaiChart,
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
      dataKrs: [],
      visible: true
    }
  },
  mounted () {
    this.ambilKrs()
  },
  methods: {
    ambilKrs () {
      const vm = this
      setTimeout(() => {
        axios.get('adminLihatKrs/' + vm.$route.params.nim).then((response) => {
          vm.dataKrs = response.data
          vm.visible = false
        })
      }, 2000)
    }
  }
}
</script>

<style lang="stylus">
.detailMahasiswa
  @media (min-width: 740px) {
    .layout-padding {
      padding 2rem 3rem
    }
    .q-list + .q-list {
      margin-top: 14px;
    }
  }
</style>
