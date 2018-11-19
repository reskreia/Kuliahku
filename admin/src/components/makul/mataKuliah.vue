<template>
  <div class="layout-padding">
    <q-data-table
      :data="table"
      :config="config"
      :columns="columns"
      @refresh="refresh"
    >

      <template slot="selection" slot-scope="props">
        <q-btn flat small round icon="edit" color="secondary" @click="changeData(props)" />
        <q-btn flat small round icon="delete_forever" color="negative" @click="delData(props)" />
      </template>

      <template slot="col-username" slot-scope="cell">
        <div class="my-label text-white bg-primary">
          {{cell.data}}
        </div>
      </template>
    </q-data-table>

    <q-modal v-model="modaltambah" :content-css="{padding: '32px', minWidth: '50vw'}">
      <h5>Tambah Mata Kuliah</h5>
        <div style="width: 100%">
        <div class="row">
          <div class="col-12">
            <q-input type="text" v-model="addMakul.nama" float-label="Nama" />
          </div>
        </div>
        <div class="row xs-gutter">
          <div class="col-4">
            <q-input type="text" v-model="addMakul.kurikulum" float-label="Kurikulum" />
          </div>
          <div class="col-8">
            <q-input type="text" v-model="addMakul.kode" float-label="Kode" />
          </div>
        </div>
        <div class="row xs-gutter">
          <div class="col-6">
            <div class="col-12">
              <q-select
                v-model="addMakul.teori"
                float-label="Teori"
               :options="dsks"
              />
            </div>
            <div class="col-12">
              <q-select
                v-model="addMakul.praktek"
                float-label="Praktek"
               :options="dsks"
              />
            </div>
          </div>
          <div class="col-6">
            <div class="col-12">
              <q-select
                v-model="addMakul.status"
                float-label="Status"
               :options="dStatus"
              />
            </div>
            <div class="col-12">
              <q-select filter
                v-model="addMakul.kategori"
                float-label="Kategori"
               :options="dataKategori"
              />
            </div>
          </div>
        </div>
        <div class="row xs-gutter">
          <div class="col-6">
          <q-select
            v-model="addMakul.semester"
            float-label="Semester"
           :options="dsms"
          />
          </div>
          <div class="col-6">
          <q-select filter
            v-model="addMakul.progdi"
            float-label="Progdi"
           :options="dataProgdi"
          />
          </div>
        </div>
        <div class="col-12">
          <q-input type="textarea" v-model="addMakul.syarat" :min-rows="1" float-label="Prasyarat" />
        </div>
          <br>
          <div class="row justify-end">
            <div >
              <q-btn loader rounded color="primary" @click="tambahMakul">
                Simpan
              </q-btn>
              <q-btn rounded color="tertiary" @click="modaltambah = false">Close</q-btn>
            </div>
          </div>
        </div>
    </q-modal>

    <q-modal v-model="modaledit" :content-css="{padding: '32px', minWidth: '50vw'}">
        <h5>Edit Dosen</h5>
        <div style="width: 100%">
        <div class="row">
          <div class="col-12">
            <q-input type="text" v-model="formedit.namaMk" float-label="Nama" />
          </div>
        </div>
        <div class="row xs-gutter">
          <div class="col-4">
            <q-input type="text" v-model="formedit.kurikulum" float-label="Kurikulum" />
          </div>
          <div class="col-8">
            <q-input type="text" v-model="formedit.kodeMk" float-label="Kode" />
          </div>
        </div>
        <div class="row xs-gutter">
          <div class="col-6">
            <div class="col-12">
              <q-select
                v-model="formedit.teori"
                float-label="Teori"
               :options="dsks"
              />
            </div>
            <div class="col-12">
              <q-select
                v-model="formedit.praktek"
                float-label="Praktek"
               :options="dsks"
              />
            </div>
          </div>
          <div class="col-6">
            <div class="col-12">
              <q-select
                v-model="formedit.status"
                float-label="Status"
               :options="dStatus"
              />
            </div>
            <div class="col-12">
              <q-select filter
                v-model="formedit.kategori"
                float-label="Kategori"
               :options="dataKategori"
              />
            </div>
          </div>
        </div>
        <div class="row xs-gutter">
          <div class="col-6">
          <q-select
            v-model="formedit.semester"
            float-label="Semester"
           :options="dsms"
          />
          </div>
          <div class="col-6">
          <q-select filter
            v-model="formedit.jurusan"
            float-label="Progdi"
           :options="dataProgdi"
          />
          </div>
        </div>
        <div class="col-12">
          <q-input type="textarea" v-model="formedit.prasyarat" :min-rows="1" float-label="Prasyarat" />
        </div>
          <br>
          <div class="row justify-center">
            <q-btn loader rounded color="primary" @click="editMakul">
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
  ActionSheet,
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
    this.ambilJurusan()
    this.ambilKategori()
  },
  methods: {
    changeData (props) {
      this.modaledit = true
      this.formedit = props.rows[0].data
    },
    delData (props) {
      const vm = this
      vm.hapusMK = props.rows[0].data.id
      ActionSheet.create({
        title: 'Hapus Mata Kuliah?',
        actions: [
          {
            label: 'Ya',
            icon: 'delete',
            handler () {
              axios.delete('adminHapusMakul/' + vm.hapusMK).then((response) => {
                vm.readData()
                Toast.create.positive({
                  html: 'Mata Kuliah terhapus.',
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
    },
    refresh (done) {
      this.timeout = setTimeout(() => {
        axios.get('adminMasterKrs').then(response => {
          this.table = response.data
        })
        done()
      }, 1500)
    },
    readData () {
      axios.get('adminMasterKrs').then(response => {
        this.table = response.data
      })
    },
    ambilJurusan () {
      axios.get('adminAmbilJurusan').then(response => {
        this.dataProgdi = response.data
      })
    },
    ambilKategori () {
      axios.get('adminAmbilKategori').then(response => {
        this.dataKategori = response.data
      })
    },
    tambahMakul (e, done) {
      const vm = this
      axios.post('adminAddMakul', vm.addMakul).then((response) => {
        setTimeout(() => {
          done()
          vm.addMakul = { 'kode': '', 'nama': '', 'teori': '', 'praktek': '', 'progdi': '', 'kategori': '', 'status': '' }
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
    editMakul (e, done) {
      const vm = this
      axios.patch('adminEditMakul/' + vm.formedit.id, {
        kurikulum: vm.formedit.kurikulum,
        kode: vm.formedit.kodeMk,
        nama: vm.formedit.namaMk,
        teori: vm.formedit.teori,
        praktek: vm.formedit.praktek,
        progdi: vm.formedit.jurusan,
        kategori: vm.formedit.kategori,
        status: vm.formedit.status,
        semester: vm.formedit.semester,
        prasyarat: vm.formedit.prasyarat
      }).then((response) => {
        setTimeout(() => {
          done()
          vm.modaledit = false
          vm.readData()
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
      dataProgdi: [],
      hapusMK: '',
      dataKategori: [],
      dStatus: [
        {
          label: 'Wajib',
          value: 1
        },
        {
          label: 'Pilihan',
          value: 2
        }
      ],
      dsms: [
        {
          label: 'Semester 1',
          value: 1
        },
        {
          label: 'Semester 2',
          value: 2
        },
        {
          label: 'Semester 3',
          value: 3
        },
        {
          label: 'Semester 4',
          value: 4
        },
        {
          label: 'Semester 5',
          value: 5
        },
        {
          label: 'Semester 6',
          value: 6
        },
        {
          label: 'Semester 7',
          value: 7
        },
        {
          label: 'Semester 8',
          value: 8
        }
      ],
      dsks: [
        {
          label: '0',
          value: 0
        },
        {
          label: '1',
          value: 1
        },
        {
          label: '2',
          value: 2
        },
        {
          label: '3',
          value: 3
        },
        {
          label: '4',
          value: 4
        },
        {
          label: '5',
          value: 5
        },
        {
          label: '6',
          value: 6
        }
      ],
      addMakul: {
        kode: '',
        nama: '',
        teori: 0,
        praktek: 0,
        progdi: '',
        kategori: '',
        status: '',
        semester: '',
        syarat: ''
      },
      formedit: {},
      config: {
        title: 'Daftar Mata Kuliah',
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
