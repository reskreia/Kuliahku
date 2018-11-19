<template>
  <div class="layout-padding">
    <q-uploader 
      stack-label="UNGGAH BERKAS"
      name="file"
      :url="getSignedUrl"
      method="POST" />    
    <br>
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
    </q-data-table>
  </div>
</template>

<script>
import {
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
  clone,
  QList,
  QListHeader,
  QItem,
  QItemSeparator,
  QItemSide,
  QItemMain,
  QUploader,
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
    QUploader,
    QItemTile
  },
  mounted () {
    this.readData()
  },
  methods: {
    readData () {
      axios.get('daftarMahasiswa').then(response => {
        this.table = response.data
      })
    },
    refresh (done) {
      this.timeout = setTimeout(() => {
        axios.get('daftarMahasiswa').then(response => {
          this.table = response.data
        })
        done()
      }, 1500)
    }
  },
  beforeDestroy () {
    clearTimeout(this.timeout)
  },
  data () {
    return {
      table: [],
      modaledit: false,
      getSignedUrl: 'http://127.0.0.1:8000/api/akademik/nim',
      config: {
        title: 'Daftar NIM Mahasiswa',
        noHeader: false,
        refresh: true,
        columnPicker: true,
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
          type: 'string'
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
