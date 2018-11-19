import axios from 'axios'
import {Doughnut} from 'vue-chartjs'

export default {
  extends: Doughnut,
  mounted () {
    axios.get('nilaiRata')
      .then(resp => {
        this.rows = resp.data.angka
        this.labels = resp.data.label
        this.setUpGraph()
      })
  },
  data () {
    return {
      rows: [],
      labels: [],
      bgr: ['#2196f3', '#009688', '#9c27b0', '#4caf50', '#e91e63'],
      hbg: ['#2196f3', '#009688', '#9c27b0', '#4caf50', '#e91e63']
    }
  },
  methods: {
    setUpGraph () {
      this.renderChart({
        labels: this.labels,
        datasets: [
          {
            label: 'SKS',
            data: this.rows,
            backgroundColor: this.bgr,
            hoverBackgroundColor: this.hbg
          }
        ]
      }, {responsive: true, maintainAspectRatio: false})
    }
  }
}
