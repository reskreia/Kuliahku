<template>
  <div class="layout-padding smskrs row justify-center">
    <div style="width: 100%; max-width: 100vw;">
      <p>Menu ini untuk memasukan krs di semester anda.</p>
        <div>
          <q-select float-label="Semester"
            v-validate="'required'" separator color="indigo" inverted 
            v-model="tambahkrs.pickSemester" disable
            :options="dataSemester"
          />

          <q-select chips separator float-label="Krs" multiple
            filter v-validate="'required'" frame-color="dark" inverted color="teal"
            v-model="tambahkrs.idKrs" filter-placeholder="Cari nama makul"
            :options="dataKrs"
          />
        <br>
          <q-btn loader class="full-width" color="primary" @click="submitKrs">
            Simpan
            <span slot="loading">
              <q-spinner-puff class="on-left" />
              Menyimpan...
            </span>
          </q-btn>
      </div>
      <br>
      <q-list link>
        <q-list-header>LOG KRS</q-list-header>
        <q-item separator v-for="(l, index) in logKrs" :key="index" v-on:click="confirmDelete(l, index)">
          <q-item-main>
            <q-item-tile label class="row items-center">
              {{ l.kodeMkl }}</q-item-tile>
              <q-item-tile sublabel>{{ l.namaMkl }}
                <br>
                <div v-if="l.status === 'Baru'">
                  <span class="text-blue-9">#semester-{{ l.namaSms }}</span> 
                  <span class="text-blue-9">#baru</span>
                </div>
                <div v-else-if="l.status == 'Mengulang'">
                  <span class="text-blue-9">#semester-{{ l.namaSms }}</span> 
                   <span class="text-red-7">#ulang</span>
                </div>
                <small>{{ l.created_at | moment("from", "now") }}</small>
            </q-item-tile>
          </q-item-main>
          <q-item-side right color="red" icon="clear" />
        </q-item>
      </q-list>     
    </div>
  </div>
</template>

<script>
import {
  QList,
  QListHeader,
  QItem,
  QItemSeparator,
  QItemMain,
  QItemTile,
  QItemSide,
  QIcon,
  QSelect,
  Toast,
  QSpinnerPuff,
  ActionSheet,
  QBtn
} from 'quasar'
import axios from 'axios'

export default {
  components: {
    QList,
    QListHeader,
    QItem,
    QItemSeparator,
    QItemMain,
    QItemTile,
    QItemSide,
    QIcon,
    QSelect,
    QSpinnerPuff,
    QBtn
  },
  data () {
    return {
      tambahkrs: {
        pickSemester: '',
        idKrs: []
      },
      logKrs: [],
      dataSemester: [],
      dataKrs: []
    }
  },
  mounted () {
    this.listSMS()
    this.ambilKRS()
  },
  methods: {
    listSMS () {
      axios.get('ambilSMS').then((response) => {
        if (response.data.length === 0) {
          this.$router.push({ name: 'inputSMS' })
          Toast.create.info({
            html: 'Tambahkan semester terlebih dahulu.',
            timeout: 1000
          })
        }
        else {
          this.dataSemester = response.data
          this.tambahkrs.pickSemester = this.dataSemester[0].value
        }
      })
    },
    ambilKRS () {
      axios.get('ambilKrs').then((response) => {
        this.dataKrs = response.data
      })
    },
    readKrs () {
      axios.get('listKRS').then(response => {
        this.logKrs.unshift(response.data)
      })
    },
    submitKrs (e, done) {
      const vm = this
      vm.$validator.validateAll().then((result) => {
        if (result) {
          axios.post('addKRS', vm.tambahkrs).then((response) => {
            setTimeout(() => {
              done()
              Toast.create.positive({
                html: 'Krs berhasil di tambahkan.',
                timeout: 1000
              })
              vm.logKrs = response.data
            }, 1500)
          }).catch((error) => {
            setTimeout(() => {
              done()
              error = Toast.create.warning({
                html: 'Terjadi kesalahan.',
                timeout: 1500
              })
            }, 1500)
          })
          return
        }
        setTimeout(() => {
          done()
          Toast.create.negative({
            html: 'Data tidak lengkap.',
            timeout: 1500
          })
        }, 1500)
      })
    },
    confirmDelete (lObj) {
      const vm = this
      ActionSheet.create({
        title: 'Hapus krs?',
        actions: [
          {
            label: 'Ya',
            icon: 'delete',
            handler () {
              axios.post('mDelKrs', lObj).then((response) => {
                vm.logKrs.splice(vm.logKrs.indexOf(lObj), 1)
                Toast.create.positive({
                  html: 'Krs terhapus.',
                  timeout: 1000
                })
              }).catch((error) => {
                Toast.create.negative(error.response.data.message)
              })
            }
          }
        ],
        dismiss: {
          label: 'Batal',
          handler () {
            Toast.create.info({
              html: 'Membatalkan.',
              timeout: 1000
            })
          }
        }
      })
    }
  }
}
</script>

<style lang="styl">
.smskrs
  .p
    line-height: 15px;
</style>