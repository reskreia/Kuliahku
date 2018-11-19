import axios from 'axios'
import {Bar} from 'vue-chartjs'

export default {
  extends: Bar,
  mounted () {
    axios.get('adminChartKrs/' + this.$route.params.nim)
      .then(resp => {
        this.rows = resp.data.rows
        this.labels = resp.data.labels
        this.setUpGraph()
      })
    this.gradient2 = this.$refs.canvas.getContext('2d').createLinearGradient(0, 0, 0, 600)
    this.gradient2.addColorStop(0, 'rgba(0, 231, 255, 0.9)')
    this.gradient2.addColorStop(0.5, 'rgba( 66, 188, 250, 0.893 )')
  },
  data () {
    return {
      rows: [],
      labels: [],
      gradient: null
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
            borderColor: 'rgba( 26, 171, 244, 0.964 )',
            hoverBorderWidth: 1,
            hoverBorderColor: 'purple',
            backgroundColor: this.gradient2,
            hoverBackgroundColor: 'rgba( 8, 201, 231, 0.964 )'
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
