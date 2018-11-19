<template>
  <div class="layout-padding">
    <q-data-table
      :data="table"
      :config="config"
      :columns="columns"
      @refresh="refresh"
    >
      <template slot="col-username" slot-scope="cell">
        <div class="my-label text-white bg-primary">
          {{cell.data}}
        </div>
      </template>

      <template slot='col-daksi' slot-scope='cell'>
        <q-btn flat color="secondary" @click="changeData(cell.row)">
          Edit
        </q-btn>
      </template>

    </q-data-table>
  
    <q-modal v-model="modaltambah" :content-css="{padding: '32px', minWidth: '50vw'}">
      <h5>Tambah Dosen</h5>
        <div>
          <q-input type="text" v-model="credentials.nip" float-label="NIP" />
         <q-input type="text" v-model="credentials.name" float-label="Nama" />
          <q-select
            v-model="credentials.fakultas"
            float-label="Fakultas"
            clearable
           :options="dataFakultas"
          />
         <q-input type="email" v-model="credentials.email" float-label="Email" />
         <q-input type="password" v-model="credentials.password" float-label="Password" /> 
          <br>
          <div class="row justify-center">
            <q-btn loader rounded color="primary" @click="tambahDosen">
              Simpan
            </q-btn>
          </div>
        </div>
    </q-modal>

  <q-modal v-model="modaledit" :content-css="{padding: '32px', minWidth: '50vw'}">
      <h5>Edit Dosen</h5>
        <div>
          <q-input type="text" v-model="formedit.nip" float-label="NIP" />
         <q-input type="text" v-model="formedit.nama" float-label="Nama" />
          <q-select
            v-model="formedit.fid"
            float-label="Fakultas"
           :options="dataFakultas"
          />
         <q-input type="email" v-model="formedit.email" float-label="Email" />
         <q-input v-model="formedit.password" type="password" float-label="Kata sandi" />

          <br>
          <div class="row justify-center">
          <q-btn loader rounded color="primary" @click="validateEdit">
            Perbarui
          </q-btn>
          </div>
        </div>
  </q-modal>

  <!-- Non-expandable on a QLayout -->
  <q-fixed-position corner="bottom-right" :offset="[18, 18]">
    <q-btn small round color="blue-10" @click="modaltambah = true">
      <q-icon name="create" />
    </q-btn>
  </q-fixed-position>
</div>
</template>

<script>
import {
  QFixedPosition,
  QModal,
  Toast,
  QDataTable,
  QField,
  QInput,
  QSelect,
  QBtn,
  QIcon,
  clone
} from 'quasar'
import axios from 'axios'

export default {
  components: {
    QFixedPosition,
    QModal,
    QDataTable,
    QField,
    QInput,
    QSelect,
    QBtn,
    QIcon
  },
  mounted () {
    this.readData()
    this.ambilFakultas()
  },
  methods: {
    changeData (row) {
      this.modaledit = true
      this.formedit = row
    },
    refresh (done) {
      this.timeout = setTimeout(() => {
        axios.get('adminDosen').then(response => {
          this.table = response.data
        })
        done()
      }, 1500)
    },
    readData () {
      axios.get('adminDosen').then(response => {
        this.table = response.data
      })
    },
    ambilFakultas () {
      axios.get('aDF').then(response => {
        this.dataFakultas = response.data
      })
    },
    tambahDosen (e, done) {
      const vm = this
      axios.post('dosen/tambah', vm.credentials).then((response) => {
        setTimeout(() => {
          done()
          vm.credentials = { 'nip': '', 'username': '', 'name': '', 'fakultas': null, 'email': '', 'password': '' }
          Toast.create.positive({
            html: 'Data tersimpan.',
            timeout: 1500
          })
          vm.modaltambah = false
          vm.readData()
        }, 1000)
      }).catch((error) => {
        setTimeout(() => {
          done()
          error = Toast.create.negative({
            html: 'Terjadi kesalahan.',
            timeout: 1500
          })
        }, 1500)
      })
    },
    validateEdit (e, done) {
      const vm = this
      axios.patch('adminDosen/' + vm.formedit.id, {
        nip: vm.formedit.nip,
        email: vm.formedit.email,
        fakultas: vm.formedit.fid,
        nama: vm.formedit.nama,
        password: vm.formedit.password
      }).then((response) => {
        setTimeout(() => {
          vm.modaledit = false
          done()
          Toast.create.positive({
            html: 'Data diperbarui.',
            timeout: 1500
          })
        }, 1500)
      }).catch((error) => {
        setTimeout(() => {
          done()
          error = Toast.create.negative({
            html: 'Terjadi kesalahan.',
            timeout: 1500
          })
        }, 1500)
      })
    }
  },
  beforeDestroy () {
    clearTimeout(this.timeout)
  },
  data () {
    return {
      table: [],
      modaltambah: false,
      modaledit: false,
      dataFakultas: [],
      credentials: {
        nip: '',
        username: '',
        name: '',
        fakultas: null,
        email: '',
        password: ''
      },
      formedit: {},
      config: {
        title: 'Daftar Dosen',
        refresh: true,
        noHeader: false,
        columnPicker: true,
        leftStickyColumns: 0,
        rightStickyColumns: 0,
        bodyStyle: {
          maxHeight: '600px'
        },
        rowHeight: '40px',
        responsive: true,
        pagination: {
          rowsPerPage: 10,
          options: [10, 15, 30, 50, 100, 200]
        },
        messages: {
          noData: 'Tidak ada data untuk ditampilkan.',
          noDataAfterFiltering: 'Hasil pencarian tidak ada, silahkan cek kembali.'
        },
        labels: {
          columns: 'Kolom',
          allCols: 'Semua kolom',
          rows: 'Tampilkan',
          selected: {
            singular: 'terpilih.',
            plural: 'terpilih.'
          },
          clear: 'batal',
          search: 'Cari',
          all: 'Semua'
        }
      },

      columns: [
        {
          label: 'NIP',
          field: 'nip',
          filter: true,
          type: 'string'
        },
        {
          label: 'Nama Dosen',
          field: 'nama',
          filter: true,
          sort: true,
          type: 'string'
        },
        {
          label: 'Fakultas',
          field: 'fakultas',
          filter: true,
          sort: true,
          type: 'string'
        },
        {
          label: 'Aksi',
          field: 'daksi'
        }
      ],
      pagination: true,
      rowHeight: 40,
      bodyHeightProp: 'maxHeight',
      bodyHeight: 500
    }
  },
  watch: {
    pagination (value) {
      if (!value) {
        this.oldPagination = clone(this.config.pagination)
        this.config.pagination = false
        return
      }
      this.config.pagination = this.oldPagination
    },
    rowHeight (value) {
      this.config.rowHeight = value + 'px'
    },
    bodyHeight (value) {
      let style = {}
      if (this.bodyHeightProp !== 'auto') {
        style[this.bodyHeightProp] = value + 'px'
      }
      this.config.bodyStyle = style
    },
    bodyHeightProp (value) {
      let style = {}
      if (value !== 'auto') {
        style[value] = this.bodyHeight + 'px'
      }
      this.config.bodyStyle = style
    }
  }
}
</script>

<style lang="stylus">
.my-label
  padding 3px
  border-radius 2px
  display inline-block
</style>
