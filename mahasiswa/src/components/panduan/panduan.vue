<template>
  <div class="layout-padding row justify-center">
    <div style="width: 100%; max-width: 100vw;">
      <q-stepper ref="stepper2" color="blue-10" vertical>
        <q-step default name="perkenalan" title="Apa sih Kuliahku?">
          <p>
            Aplikasi ini bisa disebut memanajemen atau mengontrol di dalam akademik. Dengan adanya aplikasi ini diharapkan bisa lebih mempermudah mahasiswa dalam masa perkuliahan.
          </p>
          <q-stepper-navigation>
            <q-btn color="primary" @click="$refs.stepper2.next()">Lanjut</q-btn>
          </q-stepper-navigation>
        </q-step>

        <q-step name="mengapa" title="Mengapa Kuliahku?" subtitle="Mengapa aplikasinya begini?">
          <p>Jangannnnnnnn kauuu mempertanyakan. xD #naif #posesif</p>

          <q-stepper-navigation>
            <q-btn color="primary" @click="$refs.stepper2.next()">Lanjut</q-btn>
            <q-btn color="secondary" flat @click="$refs.stepper2.previous()">Kembali</q-btn>
          </q-stepper-navigation>
        </q-step>

        <q-step name="informasi" title="Informasi Penting!">
          <p>
            Saya akan jelaskan sedikit, menu mahasiswa ada master data, menu krs, dan menu khs. Ketiga itu saling terhubung, karena saling terhubung mohon lebih teliti dalam memasukan data.
          </p>

          <q-stepper-navigation>
            <q-btn color="primary" @click="$refs.stepper2.next()">Lanjut</q-btn>
            <q-btn color="secondary" flat @click="$refs.stepper2.previous()">Kembali</q-btn>
          </q-stepper-navigation>
        </q-step>

        <q-step name="tutorial" title="Penggunaan">
          <p>
            1. Anda harus mengisi data semester dahulu dengan masuk ke master data.<br>
            2. Anda tidak bisa membuat langsung banyak, buatlah satu terlebih dahulu.<br>
            3. Setelah itu masuk ke krs di master data, masukan krs yang anda ambil. Ingat pilihan semester hanya akan muncul semester terbaru dalam waktu 24 jam. :) <br>
            5. Apabila ada kesalahan untuk pengisian krs maka anda harus menghapus dari log krs yang tersedia dibawahnya apabila anda lupa menghapus maka anda hrus hapus data semester itu. :) <br>
            6.Untuk pengisian nilai bila terjadi kesalahan silahkan pilih reset untuk meresetnya. nilai tinggi otomatis akan terpilih dan apabila anda salah mengisi datanya di semester itu silahkan untuk menyertakan reset ketika pengisian nilai. <br> <span class="text-bold">
            7. Data semester adalah induknya, bila dihapus maka data krs dan khs anda akan terhapus juga.</span><br>
            8. Di mohon untuk lebih teliti.
          </p>

          <q-stepper-navigation>
            <q-btn color="primary" @click="$refs.stepper2.next()">Lanjut</q-btn>
            <q-btn color="secondary" flat @click="$refs.stepper2.previous()">Kembali</q-btn>
          </q-stepper-navigation>
        </q-step>

        <q-step name="penutup" title="Penutup">
          <p>
            Di mohon tidak terlalu bergantung pada data di aplikasi ini, anda harus tetap rutin bimbingan dengan dosen dan sesekali cocokanlah dengan data yang asli seperti tranksipsi nilai anda, dll.<br>Karena pada dasarnya aplikasi ini hanya membantu dalam merekap data perkuliahan anda dan mungkin bisa dipergunakan lebih cpeat jika anda membutuhkan data kuliah anda.<br>
            Semoga aplikasi ini bermanfaat dan bisa membantu dalam masa perkuliahan anda. Raih mimpi anda, salam luar biasa.
          </p>

          <q-stepper-navigation>
            <q-btn color="primary" @click="$refs.stepper2.goToStep('perkenalan')">Awal</q-btn>
            <q-btn color="secondary" flat @click="$refs.stepper2.previous()">Kembali</q-btn>
          </q-stepper-navigation>
        </q-step>

      </q-stepper>

    </div>
  </div>
</template>

<script>
import {
  QStepper,
  QStep,
  QStepperNavigation,
  QBtn,
  QOptionGroup
} from 'quasar'
export default {
  components: {
    QStepper,
    QStep,
    QStepperNavigation,
    QBtn,
    QOptionGroup
  }
}
</script>