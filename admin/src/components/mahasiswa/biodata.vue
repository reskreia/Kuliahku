<template>
  <div class="detailBio">
    <div class="layout-padding row">
      <div class="col-12">
        <q-list highlight inset-separator>
        	<q-item>
                <q-item-side icon="ion-ios-locked-outline" color="primary" />
                <q-item-main>
                    <q-item-tile label>
                      <div class="row">
                        <div class="col-xs-11 col-md-2">
                          <span class="text-faded">Nim</span>
                        </div>
                        <div class="col-xs-1 col-md-1">:</div>
                        <div class="col-xs-12 col-md-7">{{ dataBio.nim }}</div>
                      </div>
                    </q-item-tile>
                </q-item-main>
            </q-item>
            <q-item>
                <q-item-side icon="ion-ios-person-outline" color="primary" />
                <q-item-main>
                    <q-item-tile label>
                      <div class="row">
                        <div class="col-xs-11 col-md-2">
                          <span class="text-faded">Nama</span>
                        </div>
                        <div class="col-xs-1 col-md-1">:</div>
                        <div class="col-xs-12 col-md-7">{{ dataBio.nama }}</div>
                      </div>
                    </q-item-tile>
                </q-item-main>
            </q-item>
            <q-item>
                <q-item-side icon="ion-ios-telephone-outline" color="primary" />
                <q-item-main>
                    <q-item-tile label>
                      <div class="row">
                        <div class="col-xs-11 col-md-2">
                          <span class="text-faded">No. HP</span>
                        </div>
                        <div class="col-xs-1 col-md-1">:</div>
                        <div class="col-xs-12 col-md-7">{{ dataBio.hp }}</div>
                      </div>
                    </q-item-tile>
                </q-item-main>
            </q-item>
            <q-item>
                <q-item-side icon="ion-ios-wineglass-outline" color="primary" />
                <q-item-main>
                    <q-item-tile label>
                      <div class="row">
                        <div class="col-xs-11 col-md-2">
                          <span class="text-faded">Kelas</span>
                        </div>
                        <div class="col-xs-1 col-md-1">:</div>
                        <div class="col-xs-12 col-md-7">{{ dataBio.kelas }}</div>
                      </div>
                    </q-item-tile>
                </q-item-main>
            </q-item>
            <q-item>
                <q-item-side icon="ion-ios-home-outline" color="primary" />
                <q-item-main>
                    <q-item-tile label>
                      <div class="row">
                        <div class="col-xs-11 col-md-2">
                          <span class="text-faded">Asal</span>
                        </div>
                        <div class="col-xs-1 col-md-1">:</div>
                        <div class="col-xs-12 col-md-7">{{ dataBio.asal }}</div>
                      </div>
                    </q-item-tile>
                 </q-item-main>
            </q-item>
            <q-item>
                <q-item-side icon="ion-ios-calendar-outline" color="primary" />
                <q-item-main>
                  <q-item-tile label>
                    <div class="row">
                      <div class="col-xs-11 col-md-2">
                        <span class="text-faded">Lahir</span>
                      </div>
                      <div class="col-xs-1 col-md-1">:</div>
                      <div class="col-xs-12 col-md-7">{{ dataBio.lahir | moment("Do MMMM YYYY") }}</div>
                    </div>
                  </q-item-tile>
                </q-item-main>
            </q-item>
            <q-item>
                <q-item-side icon="ion-ios-body-outline" color="primary" />
                <q-item-main>
                    <div class="row">
                      <div class="col-xs-11 col-md-2">
                        <span class="text-faded">Kelamin</span>
                      </div>
                      <div class="col-xs-1 col-md-1">:</div>
                      <div class="col-xs-12 col-md-7">{{ dataBio.kelamin }}</div>
                    </div>
                </q-item-main>
            </q-item>
            <q-item>
                <q-item-side icon="ion-ios-briefcase-outline" color="primary" />
                <q-item-main>
                    <div class="row">
                      <div class="col-xs-11 col-md-2">
                        <span class="text-faded">Bekerja</span>
                      </div>
                      <div class="col-xs-1 col-md-1">:</div>
                      <div class="col-xs-12 col-md-7">{{ dataBio.bekerja }}</div>
                    </div>
                </q-item-main>
            </q-item>
            <q-item>
                <q-item-side icon="ion-ios-navigate-outline" color="primary" />
                <q-item-main>
                  <q-item-tile label>
                    <div class="row">
                      <div class="col-xs-11 col-md-2">
                        <span class="text-faded">Keterangan</span>
                      </div>
                      <div class="col-xs-1 col-md-1">:</div>
                      <div class="col-xs-12 col-md-7">{{ dataBio.keterangan }}</div>
                    </div>
                  </q-item-tile>
                </q-item-main>
            </q-item>
        </q-list>
      </div>
    </div>
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
  QItemTile,
  Toast
} from 'quasar'
import axios from 'axios'

export default {
  components: {
    QBtn,
    QIcon,
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
      dataBio: []
    }
  },
  mounted () {
    this.ambilBiodata()
  },
  methods: {
    ambilBiodata () {
      axios.get('adminLihatBiodata/' + this.$route.params.nim).then((response) => {
        if (response.data.length === 0) {
          Toast.create.info('tidak ada data.')
        }
        else {
          this.dataBio = response.data
        }
      })
    }
  }
}
</script>

<style lang="stylus">
.detailBio 
  @media (min-width: 850px) {
    .layout-padding {
      padding 4rem 10rem
    }
    .q-list + .q-list {
      margin-top: 14px;
    }
  }
</style>
