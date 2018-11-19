import axios from 'axios'
import { Pie } from 'vue-chartjs'

export default {
  extends: Pie,
  mounted () {
    axios.get('adminFakultasChart')
      .then(resp => {
        this.rows = resp.data.rows
        this.labels = resp.data.labels
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
            label: 'Total Mahasiswa',
            data: this.rows,
            backgroundColor: this.bgr,
            hoverBackgroundColor: this.hbg
          }
        ]
      },
      {
        responsive: true,
        maintainAspectRatio: false
      })
    }
  }
}
