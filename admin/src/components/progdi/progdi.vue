<template>
  <div class="layout-padding">
    <q-data-table
      :data="table"
      :config="config"
      :columns="columns"
      @refresh="refresh"
      @selection="selection"
    >
      <template slot='col-maksi' slot-scope='cell'>
        <q-btn flat small rounded no-caps color="teal" @click="detailData(cell.row.idJurusan)">Detail</q-btn>
        <q-btn flat small rounded no-caps color="red" @click="changeData(cell.row)" >Edit</q-btn>
      </template>
    </q-data-table>
  
    <q-modal v-model="modaltambah" :content-css="{padding: '32px', minWidth: '50vw'}">
      <h5>Tambah Program Studi</h5>
        <div>
          <q-select type="text" v-model="formprogdi.fakultas_id" float-label="Nama Fakultas" :options="dataFakultas" /> 
          <q-select filter type="text" v-model="formprogdi.jurusan_id" float-label="Nama Progdi" :options="dataJurusan" /> 
          <br>
          <div class="row justify-center">
            <q-btn loader rounded color="primary" @click="tambahJurusan">
              Simpan
            </q-btn>
          </div>
        </div>
    </q-modal>

  <q-modal v-model="modaledit" :content-css="{padding: '32px', minWidth: '50vw'}">
      <h5>Edit Program Studi</h5>
      <div>
          <q-select type="text" v-model="formedit.fid" float-label="Nama Fakultas" :options="dataFakultas" /> 
          <q-select filter type="text" v-model="formedit.jid" float-label="Nama Progdi" :options="dataJurusan" /> 
        <br>
        <div class="row justify-center">
          <q-btn loader rounded color="primary" @click="validateEdit">
            Perbarui
          </q-btn>
        </div>
      </div>
  </q-modal>

    <q-modal v-model="detailModal" ref="layoutModal" maximized :content-css="{minWidth: '80vw', minHeight: '80vh'}">
      <q-modal-layout>
        <q-toolbar slot="header" style="background: #034289">
          <q-btn flat @click="$refs.layoutModal.close()">
            <q-icon name="keyboard_arrow_left" />
          </q-btn>
          <q-toolbar-title>
            Detail Kurikulum
          </q-toolbar-title>
        </q-toolbar>

        <q-toolbar slot="footer" style="background: #034289">
          <q-toolbar-title>
            Kuliahku ©2018
          </q-toolbar-title>
        </q-toolbar>

        <div class="layout-padding">
          <div>
            <q-select
              v-model="selTahun"
              float-label="Kurikulum"
             :options="dataTahun" inverted
            />

          <q-data-table
            :data="tableMakul"
            :config="configMakul"
            :columns="columMakul"
          >

            <template slot="col-username" slot-scope="cell">
              <div class="my-label text-white bg-primary">
                {{cell.data}}
              </div>
            </template>
          </q-data-table>
          </div>
        </div>
      </q-modal-layout>
    </q-modal>

  <!-- Non-expandable on a QLayout -->
  <q-fixed-position corner="bottom-right" :offset="[18, 18]">
    <q-btn small round color="blue-10" @click="modaltambah = true">
      <q-icon name="create" />
    </q-btn>
  </q-fixed-position>
  {{ krs }}
</div>
</template>

<script>
import {
  QFixedPosition,
  QModal,
  QModalLayout,
  QToolbar,
  QToolbarTitle,
  Toast,
  QDataTable,
  QField,
  QInput,
  QCheckbox,
  QSelect,
  QSlider,
  QBtn,
  QIcon,
  QTooltip,
  clone,
  QList,
  QItem,
  QItemSide,
  QItemMain
} from 'quasar'
import axios from 'axios'

export default {
  components: {
    QFixedPosition,
    QModal,
    QModalLayout,
    QToolbar,
    QToolbarTitle,
    QDataTable,
    QField,
    QInput,
    QCheckbox,
    QSelect,
    QSlider,
    QBtn,
    QIcon,
    QTooltip,
    QList,
    QItem,
    QItemSide,
    QItemMain
  },
  mounted () {
    this.readData()
    this.ambilProgdi()
    this.ambilFakultas()
  },
  computed: {
    krs () {
      axios.get('adminKrsKurikulum/' + this.idJur + '/' + this.selTahun).then((response) => {
        this.tableMakul = response.data
      })
    }
  },
  methods: {
    changeData (row) {
      this.modaledit = true
      this.formedit = row
    },
    detailData (idJurusan) {
      this.idJur = idJurusan
      axios.get('adminKurikulum/' + idJurusan).then(response => {
        // this.detailModal = true
        if (response.data.length === 0) {
          // this.detailModal = true
        }
        else {
          this.dataTahun = response.data
          this.detailModal = true
        }
      })
    },
    refresh (done) {
      this.timeout = setTimeout(() => {
        axios.get('adminProgdi').then(response => {
          this.table = response.data
        })
        done()
      }, 1500)
    },
    selection (number, rows) {
      console.log(`selected ${number}: ${rows}`)
    },
    readData () {
      axios.get('adminProgdi').then(response => {
        this.table = response.data
      })
    },
    ambilFakultas () {
      axios.get('adminAmbilFakultas').then(response => {
        this.dataFakultas = response.data
      })
    },
    ambilProgdi () {
      axios.get('adminAmbilJurusan').then(response => {
        this.dataJurusan = response.data
      })
    },
    tambahJurusan (e, done) {
      const vm = this
      axios.post('adminProgdi', vm.formprogdi).then((response) => {
        setTimeout(() => {
          done()
          vm.formprogdi = { 'jurusan_id': '', 'fakultas_id': '' }
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
      axios.patch('adminProgdi/' + vm.formedit.id, {
        jurusan_id: vm.formedit.jid,
        fakultas_id: vm.formedit.fid
      }).then((response) => {
        setTimeout(() => {
          vm.modaledit = false
          vm.readData()
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
      tableMakul: [],
      modaltambah: false,
      modaledit: false,
      detailModal: false,
      detailKurikulum: [],
      dataFakultas: [],
      dataJurusan: [],
      idJur: '',
      selTahun: '',
      dataTahun: [],
      formprogdi: {
        jurusan_id: '',
        fakultas_id: ''
      },
      formedit: {},
      config: {
        title: 'Daftar Kurikulum',
        refresh: true,
        noHeader: false,
        columnPicker: true,
        leftStickyColumns: 0,
        rightStickyColumns: 0,
        bodyStyle: {
          maxHeight: '500px'
        },
        rowHeight: '50px',
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
          label: 'Progdi',
          field: 'jurusan',
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
          field: 'maksi'
        }
      ],
      configMakul: {
        title: 'Daftar Mata Kuliah',
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
      columMakul: [
        {
          label: 'Kode',
          field: 'kodeMk',
          filter: true,
          sort: true,
          type: 'string',
          width: '100px'
        },
        {
          label: 'Kurikulum',
          field: 'kurikulum',
          filter: true,
          sort: true,
          type: 'nubmer',
          width: '100px'
        },
        {
          label: 'Nama',
          field: 'namaMk',
          filter: true,
          sort: true,
          type: 'string',
          width: '240px'
        },
        {
          label: 'Sks',
          field: 'sks',
          sort: true,
          type: 'number',
          width: '70px'
        },
        {
          label: 'Teori',
          field: 'teori',
          sort: true,
          type: 'number',
          width: '80px'
        },
        {
          label: 'Praktek',
          field: 'praktek',
          sort: true,
          type: 'number',
          width: '100px'
        },
        {
          label: 'Prasyarat',
          field: 'prasyarat',
          filter: true,
          sort: true,
          type: 'string',
          width: '180px'
        },
        {
          label: 'Program Studi',
          field: 'progdi',
          filter: true,
          sort: true,
          type: 'string',
          width: '200px'
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
