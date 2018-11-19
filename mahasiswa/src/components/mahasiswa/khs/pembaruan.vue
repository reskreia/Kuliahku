<template>
  <div class="layout-padding row justify-center khsbaru">
    <div style="width: 100%; max-width: 100vw;" v-show="showData">

      <q-list dense inset-separator v-for="(dk, index) in mKHS" :key="index">
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

    <q-inner-loading :visible="visible">
       <q-spinner-gears size="50px" color="primary"></q-spinner-gears>
    </q-inner-loading> 

  </div>
</template>

<script>
import {
  QBtn,
  QIcon,
  QSearch,
  QSpinnerPuff,
  QSelect,
  QList,
  QListHeader,
  QItem,
  QItemSeparator,
  QItemSide,
  QItemMain,
  QInput,
  QTransition,
  QFixedPosition,
  QInnerLoading,
  QSpinnerGears,
  QItemTile
} from 'quasar'
import axios from 'axios'

export default{
  data () {
    return {
      title: '',
      mKHS: [],
      uMakul: '',
      modaledit: false,
      isShow: false,
      showData: false,
      visible: true
    }
  },
  mounted () {
    this.ambilKhs()
  },
  methods: {
    ambilKhs () {
      setTimeout(() => {
        axios.get('ambilUlang').then((response) => {
          this.mKHS = response.data
          this.showData = true
          this.visible = false
        })
      }, 1500)
    }
  },
  components: {
    QBtn,
    QIcon,
    QSearch,
    QSpinnerPuff,
    QInput,
    QSelect,
    QList,
    QListHeader,
    QItem,
    QItemSeparator,
    QItemSide,
    QItemMain,
    QTransition,
    QFixedPosition,
    QInnerLoading,
    QSpinnerGears,
    QItemTile
  }
}
</script>

<style lang="stylus">
.khsbaru
  .q-list
    margin-top 0px
    margin-bottom 8px
  .q-list + .q-list
    margin-top 0px
</style>
