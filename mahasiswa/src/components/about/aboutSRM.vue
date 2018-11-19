<template>
  <div class="layout-padding card-examples row items-start">
    <q-card inline style="width: 100%">
      <q-card-media overlay-position="top">
        <img src="~assets/bg-usm.jpg">
        <q-card-title slot="overlay">
          Sistem Monitoring Akademik
          <span slot="subtitle">Universitas Semarang</span>
        </q-card-title>
      </q-card-media>
      <q-card-main>
          <p>Aplikasi <span class="strong">Kuliahku</span> atau <span class="strong">Sistem Monitoring Akademik</span> ini dibangun untuk membantu proses belajar didalam Akademik untuk memudahkan mahasiswa <span class="strong">Universitas Semarang</span> dalam masa perkuliahan.</p>

          <p>Diambil dari pengalaman pembuat aplikasi sendiri dan rekan-rekan seperjuangan dalam masa perkulihan ini mungkin untuk sebagian bisa atau menganggap sepele, akan tetapi ketika anda masih melanjutkan perkuliahan dan tahu-tahu ketika sudah akhir-akhir masa perkuliahan bisa jadi merugikan diri sendiri jika kita tidak cermat.</p>
          
          <p>Ini saya ambil dari pengalaman teman saya sendiri, ya mungkin dia bisa di bilang mahasiswa yang rajin dan memang selalu berangkat kuliah malahan sebenarnya dia juga bisa dikatakan wisudawan pertama dari angkatan kelas saya, ceritanya dimulai dari sini, ketika dia hendak mendaftar wisuda, setelah di cek berkas ternyata ada satu krs/makul yang belum. Saya pun juga dengarnya kaget dan bingung kok bisa gitu.</p>

          <p>Dari cerita singkat diatas, ya tentu kita tidak bisa menyalahkan pihak-pihak yang bersangkutan atau salah satu pihak saja, miss komunikasi memang bisa terjadi. Karena pada dasarnya sebuah sistem komputerisasi atau sistem yang di terapkan oleh manusia (kita sendiri) pastilah mempunyai celah. Ibarat kalau ada celah pasti kita membutuhkan alat untuk menutup celah itu kan. Maka dari itu saya (pembuat aplikasi) berinisiatif untuk membuat sistem lain yang mungkin bisa menambal atau menopang sistem yang sudah ada.</p>

          <p>Sebagai penutup, karena sistem ini masih dan selalu akan di perbaiki pasti masih ada kekurangan, baik secara teknis maupun non teknis. Untuk itu Kami menerima Kritik maupun Saran dari berbagai pihak demi memajukan dan mengembangkan <span class="strong">Sistem Monitoring Akademik Universitas Semarang</span> agar menjadi sistem yang lebih baik dan bermanfaat bagi mahasiswa.</p>
            
          <p class="quote">
            W<sup> ©2017</sup>
          </p>
      </q-card-main>
    </q-card>    
  </div>
</template>

<script>
import {
  QCard,
  QCardTitle,
  QCardMedia,
  QCardActions,
  QCardSeparator,
  QCardMain,
  QList,
  QItem,
  QItemMain,
  QItemSide,
  QItemTile,
  QCollapsible,
  QRating,
  QBtn,
  QParallax,
  QIcon,
  QPopover,
  QVideo
} from 'quasar'

export default {
  components: {
    QCard,
    QCardTitle,
    QCardMedia,
    QCardActions,
    QCardSeparator,
    QCardMain,
    QList,
    QItem,
    QItemMain,
    QItemSide,
    QItemTile,
    QCollapsible,
    QRating,
    QBtn,
    QParallax,
    QIcon,
    QPopover,
    QVideo
  }
}
</script>

<style lang="stylus">
.card-examples
  .q-card
    width 300px
    margin 2px
    border-radius 4px
  .bigger
    width 450px
  @media (max-width $breakpoint-xs-max)
    .q-card
      width 100%
</style>
