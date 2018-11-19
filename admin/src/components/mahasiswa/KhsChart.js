import axios from 'axios'
import {Line} from 'vue-chartjs'

export default {
  extends: Line,
  mounted () {
    axios.get('adminChartKhs/' + this.$route.params.nim)
      .then(resp => {
        this.rows = resp.data.nilaiSms
        this.labels = resp.data.namaSms
        this.setUpGraph()
      })
    this.gradient2 = this.$refs.canvas.getContext('2d').createLinearGradient(0, 0, 0, 450)
    this.gradient2.addColorStop(0, 'rgba(111, 194, 246, 0.81)')
    this.gradient2.addColorStop(0.5, 'rgba(111, 194, 246, 0.7)')
  },
  data () {
    return {
      rows: [],
      labels: [],
      gradient2: null
    }
  },
  methods: {
    setUpGraph () {
      this.renderChart({
        labels: this.labels,
        datasets: [
          {
            label: 'IPK',
            data: this.rows,
            borderColor: '#03a9f4',
            pointBackgroundColor: '#03a9f4',
            pointBorderColor: '#03a9f4',
            borderWidth: 1,
            backgroundColor: this.gradient2
          }
        ]
      },
      {
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true,
              userCallback: function (label, index, labels) {
                if (Math.floor(label) === label) {
                  return label
                }
              }
            }
          }]
        }
      },
      {
        responsive: true,
        maintainAspectRatio: false
      })
    }
  }
}
