<template>
  <div class="layout-padding row justify-center pedkrs">
    <div style="width: 100%; max-width: 100vw;" v-show="showData">

      <q-list dense inset-separator v-for="(sr, index) in pedomanKrs" :key="index">
        <q-list-header><strong>SEMESTER {{ sr.id }}</strong></q-list-header>
        <q-item v-for="(m, index) in sr.makul" :key="index">
          <q-item-side color="blue-grey"> <big>{{index + 1}}</big> </q-item-side>
          <q-item-main>
            <q-item-tile label>{{ m.kodeMk }}</q-item-tile>
            <q-item-tile sublabel>
              <span class="text-black">{{ m.namaMk }}</span> <br>
              Teori: {{ m.teori }},&ensp;Praktek: {{ m.praktek }}
            </q-item-tile>
          </q-item-main>
          <q-item-tile stamp>{{ m.sks }} sks <br> 
            <div v-if="m.status == '1'"><span class="text-blue-9">Wajib</span></div>
            <div v-else-if="m.status == '2'"><span class="text-teal-9">Pilihan</span></div>
          </q-item-tile>
        </q-item>
        <q-item-separator />
        <q-item>
          <q-item-main>
            <q-item-tile label>TOTAL SKS: {{ sr.makul | map(p => p.sks) | sum }}
            </q-item-tile>
          </q-item-main>
        </q-item>
      </q-list>
    </div>

     <q-inner-loading :visible="visible">
        <q-spinner-gears size="50px" color="primary"></q-spinner-gears>
    </q-inner-loading> 
  </div>
</template>

<script>
import {
  QBtn,
  QIcon,
  QList,
  QListHeader,
  QItem,
  QItemSeparator,
  QItemSide,
  QItemMain,
  QInnerLoading,
  QSpinnerGears,
  QItemTile
} from 'quasar'
import axios from 'axios'

export default{
  data () {
    return {
      title: '',
      pedomanKrs: [],
      showData: false,
      visible: true,
      editkhs: {}
    }
  },
  mounted () {
    this.ambilKrs()
  },
  methods: {
    ambilKrs () {
      const vm = this
      setTimeout(() => {
        axios.get('lihatPedoman').then((response) => {
          vm.pedomanKrs = response.data
          vm.showData = true
          vm.visible = false
        })
      }, 1500)
    }
  },
  components: {
    QBtn,
    QIcon,
    QList,
    QListHeader,
    QItem,
    QItemSeparator,
    QItemSide,
    QItemMain,
    QInnerLoading,
    QSpinnerGears,
    QItemTile
  }
}
</script>

<style lang="styl">
.pedkrs
  .q-list
    margin-top 0px
    margin-bottom 8px
  .q-list + .q-list
    margin-top 0px
</style>
