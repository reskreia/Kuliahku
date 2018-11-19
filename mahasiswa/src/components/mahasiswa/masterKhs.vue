<template>
  <div class="layout-padding row justify-center">
    <div style="width: 100%; max-width: 100vw;">
      <p>Menu ini untuk memasukan nilai ke krs yang anda ambil.</p>
      <div>
        <q-select separator float-label="Semester"
          inverted v-model="lihatSms" :options="dataSms"
        />
      </div>
      <br>
      <q-list link>
        <q-list-header>Daftar Makul</q-list-header>
        <q-item inset-separator v-for="(ks, index) in listKrs" :key="index" v-on:click="itemUp(ks)">
          <q-item-side :letter="ks.huruf" inverted color="blue" />
          <q-item-main>
            <q-item-tile label>{{ ks.kodeMk }}</q-item-tile>
            <q-item-tile sublabel>{{ ks.namaMk }}</q-item-tile>
          </q-item-main>
           <q-item-side right icon="ion-ios-loop" />
        </q-item>
      </q-list>    
    </div>

    <q-modal v-model="modalTKHS" minimized :content-css="{padding: '28px'}">
      <h5>Edit Nilai</h5>
      <div>
        <q-input type="text" disable v-model="tambahkhs.namaMk" color="grey" />
        <q-select radio
          v-model="tambahkhs.idNilai" color="blue-grey-7"
          :options="nilai"
        />
         <br>
        <q-toggle v-model="nReset" color="teal" label="Ulang" />
  
      </div>
      <br>
        <div class="row justify-center">
          <q-btn loader rounded color="primary" @click="submitEdit">
            Simpan
          </q-btn>
        </div>
    </q-modal>
    
    {{ krs }}

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
  QInput,
  QToggle,
  Toast,
  QSpinnerPuff,
  QModal,
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
    QItemSide,
    QItemTile,
    QIcon,
    QSelect,
    QInput,
    QToggle,
    QSpinnerPuff,
    QModal,
    QBtn
  },
  data () {
    return {
      tambahkhs: {},
      nilai: [
        {
          label: 'A',
          inset: true,
          value: 5
        },
        {
          label: 'B',
          inset: true,
          value: 4
        },
        {
          label: 'C',
          inset: true,
          value: 3
        },
        {
          label: 'D',
          inset: true,
          value: 2
        },
        {
          label: 'E',
          inset: true,
          value: 1
        }
      ],
      tipe: [],
      lihatSms: '',
      listKrs: [],
      dataSms: [],
      nReset: false,
      modalTKHS: false
    }
  },
  mounted () {
    this.ambilSMS()
  },
  computed: {
    krs () {
      axios.get('msKrs/' + this.lihatSms).then((response) => {
        this.listKrs = response.data
      })
    }
  },
  methods: {
    itemUp (ks) {
      this.modalTKHS = true
      this.tambahkhs = ks
    },
    submitEdit (e, done) {
      const vm = this
      axios.put('addKhsNilai/' + vm.tambahkhs.idKrs, {
        makulId: vm.tambahkhs.idMakul,
        nilai: vm.tambahkhs.idNilai,
        semester: vm.tambahkhs.semester,
        nreset: vm.nReset
      }).then((response) => {
        setTimeout(() => {
          done()
          Toast.create.positive('Data diperbarui.')
          vm.modalTKHS = false
          vm.ambilKrs()
        }, 1000)
      }).catch((error) => {
        setTimeout(() => {
          done()
          error = Toast.create.warning('Terjadi kesalahan.')
        }, 1500)
      })
    },
    ambilSMS () {
      axios.get('listSMS').then((response) => {
        this.dataSms = response.data
      })
    },
    ambilKrs () {
      axios.get('msKrs/' + this.lihatSms).then((response) => {
        this.listKrs = response.data
      })
    }
  }
}
</script>