<template>
  <div class="layout-padding">
    <q-data-table
      :data="table"
      :config="config"
      :columns="columns"
      @refresh="refresh"
      @selection="selection"
    >
      <template slot="selection" slot-scope="props">
        <q-btn flat color="secondary" @click="changeData(props)">
          <q-icon name="edit" />
        </q-btn>
      </template>
    </q-data-table>
	
    <q-modal v-model="modaltambah" :content-css="{padding: '32px', minWidth: '50vw'}">
      <h5>Tambah Fakultas</h5>
        <div>
	        <q-input type="text" v-model="formfakultas.nama" float-label="Nama Fakultas" />  
          <br>
          <div class="row justify-center">
            <q-btn loader rounded color="primary" @click="tambahFakultas">
              Simpan
            </q-btn>
          </div>
        </div>
	  </q-modal>

  <q-modal v-model="modaledit" :content-css="{padding: '32px', minWidth: '50vw'}">
      <h5>Edit Fakultas</h5>
      <div>
        <q-input type="text" v-validate="'required'" v-model="formedit.nama" float-label="Nama Fakultas" />
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
  QModalLayout,
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
  clone
} from 'quasar'
import axios from 'axios'

export default {
  components: {
    QFixedPosition,
    QModal,
    QModalLayout,
    QDataTable,
    QField,
    QInput,
    QCheckbox,
    QSelect,
    QSlider,
    QBtn,
    QIcon,
    QTooltip
  },
  mounted () {
    this.readData()
  },
  methods: {
    changeData (props) {
      this.modaledit = true
      this.formedit = props.rows[0].data
    },
    refresh (done) {
      this.timeout = setTimeout(() => {
        axios.get('adminFakultas').then(response => {
          this.table = response.data
        })
        done()
      }, 1500)
    },
    selection (number, rows) {
      console.log(`selected ${number}: ${rows}`)
    },
    readData () {
      axios.get('adminFakultas').then(response => {
        this.table = response.data
      })
    },
    tambahFakultas (e, done) {
      const vm = this
      axios.post('adminFakultas', vm.formfakultas).then((response) => {
        setTimeout(() => {
          done()
          vm.formfakultas = { 'nama': '' }
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
      axios.patch('adminFakultas/' + vm.formedit.id, {
        nama: vm.formedit.nama
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
      formfakultas: {
        nama: ''
      },
      formedit: {},
      config: {
        title: 'Daftar Fakultas',
        refresh: true,
        noHeader: false,
        columnPicker: true,
        leftStickyColumns: 1,
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
        selection: 'single',
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
          label: 'Nama Fakultas',
          field: 'nama',
          filter: true,
          sort: true,
          type: 'string'
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
