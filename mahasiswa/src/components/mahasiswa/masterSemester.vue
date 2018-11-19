<template>
<div class="tug">
  <div class="layout-padding row justify-center">
    <div v-if="items.length == 0">
      <q-alert
          color="teal-7"
          icon="info_outline"
          enter="bounceInDown"
          leave="bounceOutUp"
          appear
          dismissible
      >
        Tidak ada data.
      </q-alert>
    </div>
  	<div v-if="items.length > 0" style="width: 100%; max-width: 100vw;">
    	<div class="row" style="width: 100%">
			    <q-card inline v-for="(ds, index) in items" :key="index" style="width: 100%">
			      <q-card-title>
			        {{ ds.label }}
			        <q-icon slot="right" name="ion-ios-cloud-outline" />
			      </q-card-title>
            <q-card-main>
              <span class="text-faded">{{ ds.keterangan }}</span>
            </q-card-main>
			      <q-card-separator />
			      <q-card-actions>
              &emsp;<q-btn flat round small @click="confirmDelete(ds, index)" color="red"><q-icon name="delete" /></q-btn>&emsp;
              <q-btn flat round @click="detailOpen(ds)" small color="blue-8"><q-icon name="touch_app" /></q-btn>
			      </q-card-actions>
			    </q-card>
		  </div>
    </div>
  </div>

    <q-modal v-model="modalMakul" class="mmdsms" :content-css="{minWidth: '80vw', minHeight: '80vh'}">
      <q-modal-layout>
        <q-toolbar slot="header">
          <q-btn color="primary" flat @click="tutupModal">
            <q-icon name="ion-ios-arrow-back" />
          </q-btn>
          <q-toolbar-title>
            KRS SEMESTER
          </q-toolbar-title>
        </q-toolbar>
        <div class="layout-padding mdlkrsms">
          <q-list>
            <q-list-header>Total sks: {{ makul.length }}</q-list-header>
              <div v-if="makul === 0">
                <q-item separator>
                  <q-item-main>
                  <q-item-tile label class="row items-center">
                    Tidak ada data.
                  </q-item-tile>
                  </q-item-main>
                </q-item>
              </div>
                <q-item separator v-for="(d, index) in makul" :key="index">
                  <q-item-main>
                    <q-item-tile label class="row items-center">
                    <q-icon name="schedule" />&nbsp;
                    <small>{{ d.created_at | moment("Do MMMM YYYY") }}</small>
                  </q-item-tile>
                  <q-item-tile sublabel>
                   <dl class="horizontal">
                    <dt>Kode</dt>
                    <dd>{{ d.kodeMkl }}</dd>
                    <dt>Makul</dt>
                    <dd>{{ d.namaMkl }}</dd>
                    <dt>Status</dt>
                    <dd>
                      <span v-if="d.status === 'Baru'" class="token text-bold text-white bg-primary">
                        {{ d.status }}
                       </span>
                      <span v-else class="token text-bold text-white bg-red-8">
                        {{ d.status }}
                      </span>
                    </dd>
                    </dl>
                  </q-item-tile>
                  </q-item-main>
                  <q-item-side right :stamp="d.sks" />
                </q-item>
          </q-list>
        </div>
      </q-modal-layout>
    </q-modal>
    <q-modal v-model="modaltambah" minimized :content-css="{padding: '28px'}">
      <h5>Tambah Semester</h5>
        <q-select
        float-label="Semester" radio
        v-model="tambahsms.sms"
        :options="dsms"
        />
        <br>
        <q-toggle v-model="tambahsms.cuti" color="teal" label="Cuti" />
        <br>
        <q-input v-show="tambahsms.cuti"
          v-model="tambahsms.keterangan"
          type="textarea"
          float-label="Keterangan"
          :max-height="50" color="blue-grey-7"
          placeholder="Ex: Gasal 2017/2018 atau keterangan cuti."
          :min-rows="2"
        />
        <br><br>
        <div class="row justify-center">
          <q-btn loader rounded color="primary" @click="stambah">
            Simpan
          </q-btn>
        </div>
    </q-modal>
	<!-- Non-expandable on a QLayout -->
  <q-fixed-position corner="bottom-right" :offset="[18, 18]">
    <q-btn round color="blue-9" @click="tambahSms">
      <q-icon name="add" />
    </q-btn>
  </q-fixed-position>
</div>
</template>

<script>
import {
  QCard,
  ActionSheet,
  QCardTitle,
  QCardActions,
  QCardMain,
  QCardSeparator,
  QBtn,
  QFixedPosition,
  Toast,
  QInput,
  QToggle,
  QSelect,
  QIcon,
  QFab,
  QAlert,
  QFabAction,
  QList,
  QListHeader,
  QItem,
  QItemSeparator,
  QItemSide,
  QItemMain,
  QItemTile,
  QModal,
  QModalLayout,
  QToolbar,
  QToolbarTitle
} from 'quasar'
import axios from 'axios'

export default {
  components: {
    QCard,
    QAlert,
    QCardTitle,
    QCardActions,
    QCardMain,
    QCardSeparator,
    QBtn,
    QFixedPosition,
    QInput,
    QToggle,
    QSelect,
    QIcon,
    QFab,
    QList,
    QListHeader,
    QItem,
    QItemSeparator,
    QItemSide,
    QItemMain,
    QItemTile,
    QModal,
    QModalLayout,
    QToolbar,
    QToolbarTitle,
    QFabAction
  },
  data () {
    return {
      items: [],
      makul: [],
      tambahsms: {
        sms: '',
        keterangan: '',
        cuti: false
      },
      dsms: [
        {
          label: 'Semester 1',
          value: '1'
        },
        {
          label: 'Semester 2',
          value: '2'
        },
        {
          label: 'Semester 3',
          value: '3'
        },
        {
          label: 'Semester 4',
          value: '4'
        },
        {
          label: 'Semester 5',
          value: '5'
        },
        {
          label: 'Semester 6',
          value: '6'
        },
        {
          label: 'Semester 7',
          value: '7'
        },
        {
          label: 'Semester 8',
          value: '8'
        },
        {
          label: 'Semester 9',
          value: '9'
        },
        {
          label: 'Semester 10',
          value: '10'
        },
        {
          label: 'Semester 11',
          value: '11'
        },
        {
          label: 'Semester 12',
          value: '12'
        },
        {
          label: 'Semester 13',
          value: '13'
        },
        {
          label: 'Semester 14',
          value: '14'
        }
      ],
      modalMakul: false,
      modaltambah: false
    }
  },
  mounted () {
    this.readPosts()
  },
  methods: {
    tambahSms () {
      this.modaltambah = true
    },
    stambah (e, done) {
      const vm = this
      axios.post('addSMS', vm.tambahsms).then((response) => {
        setTimeout(() => {
          done()
          vm.readPosts()
          vm.modaltambah = false
          Toast.create.positive({
            html: 'Semester ditambahkan.',
            timeout: 1000
          })
        }, 1500)
      }).catch((error) => {
        setTimeout(() => {
          done()
          Toast.create.negative(error.response.data.message)
        }, 1500)
      })
    },
    confirmDelete (dsObj) {
      const vm = this
      ActionSheet.create({
        title: 'Hapus semester?',
        actions: [
          {
            label: 'Ya',
            icon: 'delete',
            handler () {
              axios.delete('delSMS/' + dsObj.value).then((response) => {
                vm.items.splice(vm.items.indexOf(dsObj), 1)
                vm.readPosts()
                Toast.create.positive({
                  html: 'Semester terhapus.',
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
    readPosts () {
      axios.get('belumValidSMS').then(response => {
        this.items = response.data
      })
    },
    detailOpen (dsObj) {
      const vm = this
      axios.get('detailSMS/' + dsObj.value).then((response) => {
        vm.makul = response.data
        vm.modalMakul = true
      })
    },
    tutupModal () {
      this.modalMakul = false
      this.makul = []
    }
  }
}
</script>

<style lang="styl">
.tug
  .q-card {
    margin-top 0px
    margin-right 0px
    margin-bottom 10px
    margin-left 0px
    border-radius 8px
  }
  .mmdsms
    dl
      margin-top 0
      margin-bottom 4px
    dt, dd {
      line-height 1.5
    }
  @media (max-width: 767px)
    .layout-padding {
        padding 10px 10px
    }
</style>
