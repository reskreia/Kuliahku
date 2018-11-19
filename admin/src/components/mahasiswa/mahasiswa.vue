<template>
  <div class="layout-padding">
    <q-data-table
      :data="table"
      :config="config"
      :columns="columns"
      @refresh="refresh"
    >
      <template slot="col-nim" slot-scope="cell">
        <div class="my-label text-white bg-primary">
          {{cell.data}}
        </div>
      </template>

      <template slot='col-maksi' slot-scope='cell'>
        <q-btn flat small rounded no-caps color="teal" @click="lihatBio(cell.row.nim)">Detail</q-btn>
        <q-btn flat small rounded no-caps color="red" @click="changeData(cell.row)" >Edit</q-btn>
      </template>
    </q-data-table>

    <q-modal v-model="modaledit" :content-css="{padding: '32px', minWidth: '50vw'}">
      <h5>Edit Mahasiswa</h5>
      <div>
        <q-input type="text" v-model="formedit.nim" float-label="NIM" />
        <q-input type="text" v-model="formedit.nama" float-label="Mahasiswa" />
        <q-input type="email" v-model="formedit.email" float-label="Email" />
        <q-input v-model="formedit.password" type="password" float-label="Kata sandi" />
        <q-select
          v-model="formedit.fid"
          float-label="Fakultas"
          :options="listFk"
        />
        <q-select
          v-model="formedit.pid"
          float-label="Progdi"
          :options="listProg"
        />
        <br>
        <div class="row justify-center">
          <q-btn loader rounded color="primary" @click="validateEdit">
            Perbarui
          </q-btn>
        </div>
      </div>
    </q-modal>

  {{ cariprog }}

  </div>
</template>

<script>
import {
  QModal,
  QModalLayout,
  QToolbar,
  QToolbarTitle,
  Toast,
  QDataTable,
  QField,
  QInput,
  QSelect,
  QBtn,
  QIcon,
  clone,
  QList,
  QListHeader,
  QItem,
  QItemSeparator,
  QItemSide,
  QItemMain,
  QItemTile
} from 'quasar'
import axios from 'axios'

export default {
  components: {
    QModal,
    QModalLayout,
    QToolbar,
    QToolbarTitle,
    QDataTable,
    QField,
    QInput,
    QSelect,
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
  mounted () {
    this.readData()
    this.ambilFakultas()
  },
  computed: {
    cariprog () {
      axios.get('mDaftarProgdi/' + this.formedit.fid).then((response) => {
        this.listProg = response.data
      })
    }
  },
  methods: {
    changeData (row) {
      this.modaledit = true
      this.formedit = row
    },
    lihatBio (nim) {
      this.$router.push({ name: 'mBiodata', params: { nim } })
    },
    refresh (done) {
      this.timeout = setTimeout(() => {
        axios.get('adminMahasiswa').then(response => {
          this.table = response.data
        })
        done()
      }, 1500)
    },
    readData () {
      axios.get('adminMahasiswa').then(response => {
        this.table = response.data
      })
    },
    ambilFakultas () {
      axios.get('mDaftarFakultas').then((response) => {
        this.listFk = response.data
      })
    },
    validateEdit (e, done) {
      const vm = this
      axios.patch('adminMahasiswa/' + vm.formedit.id, {
        nim: vm.formedit.nim,
        name: vm.formedit.nama,
        email: vm.formedit.email,
        fakultas: vm.formedit.fid,
        progdi: vm.formedit.pid,
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
      modaledit: false,
      listFk: [],
      listProg: [],
      formedit: {},
      nim: null,
      config: {
        title: 'Daftar Mahasiswa',
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
          label: 'NIM',
          field: 'nim',
          filter: true,
          sort: true,
          type: 'string',
          width: '120px'
        },
        {
          label: 'Nama',
          field: 'nama',
          filter: true,
          sort: true,
          type: 'string'
        },
        {
          label: 'Makul',
          field: 'makul',
          sort: true,
          type: 'string',
          width: '90px'
        },
        {
          label: 'Sks',
          field: 'sks',
          sort: true,
          type: 'number',
          width: '65px'
        },
        {
          label: 'Semester',
          field: 'sms',
          sort: true,
          type: 'number',
          width: '100px'
        },
        {
          label: 'Progdi',
          field: 'progdi',
          filter: true,
          sort: true,
          type: 'string'
        },
        {
          label: 'Aksi',
          field: 'maksi',
          width: '140px'
        }
      ],
      pagination: true,
      rowHeight: 40,
      bodyHeightProp: 'maxHeight',
      bodyHeight: 600
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
