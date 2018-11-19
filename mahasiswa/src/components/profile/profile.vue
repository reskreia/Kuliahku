<template>
<div class="profilePage">
  <div class="layout-padding row justify-center">
    <div style="width: 100%; max-width: 100vw;" v-show="tungguDulu">

      <div v-show="show" class="profAkun">
        <q-list inset-separator dense>
          <q-list-header>Detail Akun</q-list-header>
            <q-item>
              <q-item-side icon="ion-ios-locked-outline" color="primary" />
              <q-item-main>
                <q-item-tile label>Nim</q-item-tile>
                <q-item-tile sublabel>{{ akun.nim }}</q-item-tile>
              </q-item-main>
            </q-item>
            <q-item>
              <q-item-side icon="ion-ios-person-outline" color="primary" />
              <q-item-main>
                <q-item-tile label>Nama</q-item-tile>
                <q-item-tile sublabel>{{ akun.nama }}</q-item-tile>
              </q-item-main>
            </q-item>
            <q-item>
              <q-item-side icon="ion-ios-at-outline" color="primary" />
              <q-item-main>
                <q-item-tile label>Email</q-item-tile>
                <q-item-tile sublabel>{{ akun.email }}</q-item-tile>
              </q-item-main>
            </q-item>
            <q-item>
              <q-item-side icon="ion-ios-flag-outline" color="primary" />
              <q-item-main>
                <q-item-tile label>Jurusan</q-item-tile>
                <q-item-tile sublabel>{{ akun.jurusan }}</q-item-tile>
              </q-item-main>
            </q-item>
        </q-list>
      </div>

      <div v-show="show">
        <q-list inset-separator>
          <q-list-header>Biodata</q-list-header>
          <q-item>
            <q-item-side icon="ion-ios-wineglass-outline" color="primary" />
              <q-item-main>
                  <q-item-tile label>Kelas</q-item-tile>
                  <q-item-tile sublabel>{{ biodata.kelas }}</q-item-tile>
              </q-item-main>
          </q-item>
          <q-item>
            <q-item-side icon="ion-ios-home-outline" color="primary" />
            <q-item-main>
              <q-item-tile label>Asal</q-item-tile>
              <q-item-tile sublabel>{{ biodata.asal }}</q-item-tile>
            </q-item-main>
          </q-item>
          <q-item>
            <q-item-side icon="ion-ios-telephone-outline" color="primary" />
            <q-item-main>
              <q-item-tile label>No HP</q-item-tile>
              <q-item-tile sublabel>{{ biodata.hp }}</q-item-tile>
            </q-item-main>
          </q-item>
          <q-item>
            <q-item-side icon="ion-ios-calendar-outline" color="primary" />
            <q-item-main>
              <q-item-tile label>Lahir</q-item-tile>
              <q-item-tile sublabel>{{ biodata.lahir | moment("MMMM, Do YYYY") }}</q-item-tile>
            </q-item-main>
          </q-item>
          <q-item>
            <q-item-side icon="ion-ios-body-outline" color="primary" />
            <q-item-main>
              <q-item-tile label>Kelamin</q-item-tile>
              <q-item-tile sublabel>{{ biodata.kelamin }}</q-item-tile>
            </q-item-main>
          </q-item>
          <q-item>
            <q-item-side icon="ion-ios-briefcase-outline" color="primary" />
            <q-item-main>
              <q-item-tile label>Bekerja</q-item-tile>
              <q-item-tile sublabel>{{ biodata.bekerja }}</q-item-tile>
            </q-item-main>
          </q-item>
          <q-item>
            <q-item-side icon="ion-ios-navigate-outline" color="primary" />
            <q-item-main>
              <q-item-tile label>Keterangan</q-item-tile>
              <q-item-tile sublabel>{{ biodata.keterangan }}</q-item-tile>
            </q-item-main>
          </q-item>
          <q-item>
          <q-item-main>
            <br>
           <q-item-tile sublabel>
            <div class="row justify-center xs-gutter">
              <div class="col--6">
                <q-btn outline rounded color="tertiary" @click="bio">
                  Edit biodata
                </q-btn>
              </div>
              <div class="col--6">
                <q-btn outline rounded color="secondary" @click="changePass">
                  Ganti sandi
                </q-btn>
              </div>
            </div>
          </q-btn>
          </q-item-tile>
          </q-item-main>
          </q-item>
        </q-list>
      </div>


      <div v-show="gantiBio">
      <q-list>
        <q-list-header>Kelas</q-list-header>
        <q-item  tag="label">
          <q-item-side>
            <q-radio v-model="newedit.kelas" val="Reguler Pagi" />
          </q-item-side>
          <q-item-main label="Reguler Pagi" />
        </q-item>
        <q-item  tag="label">
          <q-item-side>
            <q-radio color="secondary" v-model="newedit.kelas" val="Reguler Sore" />
          </q-item-side>
          <q-item-main label="Reguler Sore" />
        </q-item>
        <q-item  tag="label">
          <q-item-side>
            <q-radio color="deep-purple" v-model="newedit.kelas" val="Karyawan" />
          </q-item-side>
          <q-item-main label="Karyawan" />
        </q-item>
        </q-list>
        <q-list>
        <q-list-header>Asal</q-list-header>
        <q-item tag="label">
          <q-item-main>
            <q-input v-model="newedit.asal" placeholder="Ex: Semarang" />
          </q-item-main>
        </q-item>
        </q-list>
        <q-list>
        <q-list-header>No HP</q-list-header>
        <q-item  tag="label">
          <q-item-main>
            <q-input v-model="newedit.hp" placeholder="Ex: 081xxx" />
          </q-item-main>
        </q-item>
        </q-list>
        <q-list>
        <q-list-header>Lahir</q-list-header>
        <q-item  tag="label">
          <q-item-main>
            <q-datetime
              v-model="newedit.lahir"
              :max="yesterday"
              type="date"
            />
          </q-item-main>
        </q-item>
        </q-list>
        <q-list>
        <q-list-header>Kelamin</q-list-header>
        <q-item  tag="label">
          <q-item-side>
            <q-radio v-model="newedit.kelamin" val="laki-laki" />
          </q-item-side>
          <q-item-main label="Laki - laki" />
        </q-item>
        <q-item  tag="label">
          <q-item-side>
            <q-radio color="pink" v-model="newedit.kelamin" val="perempuan" />
          </q-item-side>
          <q-item-main>
            <q-item-tile label>Perempuan</q-item-tile>
          </q-item-main>
        </q-item>
        </q-list>
        <q-list>
        <q-list-header>Apakah anda bekerja?</q-list-header>
        <q-item  tag="label">
          <q-item-side>
            <q-radio color="purple" v-model="newedit.bekerja" val="Ya" />
          </q-item-side>
          <q-item-main label="Ya" />
        </q-item>
        <q-item tag="label">
          <q-item-side>
            <q-radio color="red" v-model="newedit.bekerja" val="Tidak" />
          </q-item-side>
          <q-item-main>
            <q-item-tile label>Tidak</q-item-tile>
          </q-item-main>
        </q-item>
        <q-item tag="label">
          <q-item-side>
            <q-radio color="orange" v-model="newedit.bekerja" val="lain-lain" />
          </q-item-side>
          <q-item-main>
            <q-item-tile label>Lain - lain</q-item-tile>
          </q-item-main>
        </q-item>
        </q-list>
        <q-list>
        <q-list-header>Keterangan</q-list-header>
        <q-item>
          <q-item-main>
              <q-input
                v-model="newedit.keterangan"
                type="textarea"
                :max-height="50"
                :min-rows="2"
                placeholder="Ex: jika anda dari luar kota, disini anda kost / ikut saudara / sudah menetap dan alamat."
              />
          </q-item-main>
        </q-item>
        </q-list>
        <q-list>
          <q-item>
            <q-item-main>
              <br>
             <q-item-tile sublabel>
            <q-btn outline class="full-width" color="primary" @click="submitEdit">
              Simpan
            </q-btn>
            </q-item-tile>
            <q-item-tile sublabel>
            <q-btn flat class="full-width" color="grey" @click="cancelBio">
              Batal
            </q-btn>
            </q-item-tile>
            </q-item-main>
          </q-item>
        </q-list>
      </div>

      <div v-show="gantiPass">
        <q-list>
          <q-list-header>Kata sandi sekarang</q-list-header>
          <q-item>
            <q-item-main>
              <q-input v-model="pass.oldPass" type="password" />
            </q-item-main>
          </q-item>
          <q-list-header>Kata sandi baru</q-list-header>
          <q-item>
            <q-item-main>
              <q-input v-model="pass.newPass" type="password" />
            </q-item-main>
          </q-item>
          <q-item>
            <q-item-main>
              <br>
             <q-item-tile sublabel>
            <q-btn outline class="full-width" color="primary" @click="submitPass">
              Simpan
            </q-btn>
            </q-item-tile>
            <q-item-tile sublabel>
            <q-btn flat class="full-width" color="grey" @click="cancelBio">
              Batal
            </q-btn>
            </q-item-tile>
            </q-item-main>
          </q-item>
        </q-list>
      </div>

    </div>

      <q-inner-loading :visible="visible">
         <q-spinner-gears size="50px" color="blue"></q-spinner-gears>
      </q-inner-loading> 
  </div>
</div>
</template>

<script>
import {
  QList,
  QListHeader,
  QItem,
  QItemSeparator,
  QInnerLoading,
  QSpinnerGears,
  QItemSide,
  QItemMain,
  QItemTile,
  QBtn,
  QIcon,
  QSpinnerPuff,
  QRadio,
  QToggle,
  QToolbar,
  QToolbarTitle,
  QSearch,
  QInput,
  QDatetime,
  Toast,
  date
} from 'quasar'
import axios from 'axios'

const today = new Date()
const { subtractFromDate } = date

export default {
  components: {
    QList,
    QListHeader,
    QItem,
    QItemSeparator,
    QInnerLoading,
    QSpinnerGears,
    QItemSide,
    QItemMain,
    QItemTile,
    QBtn,
    QIcon,
    QSpinnerPuff,
    QRadio,
    QToggle,
    QToolbar,
    QToolbarTitle,
    QSearch,
    QDatetime,
    QInput
  },
  data () {
    return {
      akun: [],
      biodata: [],
      newedit: {},
      pass: {},
      visible: true,
      show: true,
      gantiBio: false,
      gantiPass: false,
      tungguDulu: false,
      today,
      yesterday: subtractFromDate(today, { days: 1 })
    }
  },
  mounted () {
    this.ambilData()
  },
  methods: {
    ambilData () {
      const vm = this
      setTimeout(() => {
        axios.get('mbio').then((response) => {
          vm.akun = response.data.akun[0]
          vm.tungguDulu = true
          vm.visible = false
          if (response.data.biodata.length === 0) {
            vm.biodata = response.data.biodata
            vm.newedit = {
              kelas: false,
              asal: '',
              hp: '',
              lahir: null,
              kelamin: false,
              bekerja: false,
              keterangan: ''
            }
          }
          else {
            vm.biodata = response.data.biodata[0]
            vm.newedit = {
              id: response.data.biodata[0].id,
              kelas: response.data.biodata[0].kelas,
              asal: response.data.biodata[0].asal,
              hp: response.data.biodata[0].hp,
              lahir: response.data.biodata[0].lahir,
              kelamin: response.data.biodata[0].kelamin,
              bekerja: response.data.biodata[0].bekerja,
              keterangan: response.data.biodata[0].keterangan
            }
          }
        })
      }, 2000)
    },
    submitEdit (e, done) {
      const vm = this
      axios.post('bioNE', vm.newedit).then((response) => {
        setTimeout(() => {
          done()
          Toast.create.positive({
            html: 'Data berhasil disimpan.',
            timeout: 1000
          })
          vm.ambilData()
          vm.gantiBio = false
          vm.show = true
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
    },
    submitPass (e, done) {
      const vm = this
      axios.post('password', vm.pass).then((response) => {
        setTimeout(() => {
          done()
          Toast.create.positive({
            html: 'Data berhasil disimpan.',
            timeout: 1000
          })
          vm.ambilData()
          vm.gantiPass = false
          vm.gantiBio = false
          vm.show = true
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
    },
    changePass () {
      this.show = false
      this.gantiBio = false
      this.gantiPass = true
    },
    bio () {
      this.show = false
      this.gantiPass = false
      this.gantiBio = true
    },
    cancelBio () {
      this.gantiPass = false
      this.gantiBio = false
      this.show = true
    }
  }
}
</script>

<style lang="styl">
.profilePage
  .q-list
    margin-top 0px
    margin-bottom 8px
  .q-list + .q-list
    margin-top 0px
  .profAkun {
    padding-top 0px
    padding-right 0px
    padding-bottom 10px
    padding-left 0px
  }
  @media (max-width: 767px)
    .layout-padding {
        padding 10px 10px
    }
</style>
