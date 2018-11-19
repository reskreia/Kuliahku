<template>
	<div class="layout-padding khsdash row justify-center">
		<div style="width: 100%; max-width: 100vw;" >
      <div class="row">
    		<div class="ChartKS" style="width: 100%; max-width: 100vw;">
    		  <h5 class="text-center">IPK Semester</h5>
    		  <khs-chart :height="210" />
    		</div>
      </div>
    
      <div class="row" >
        <div class="ChartPN" style="width: 100%; max-width: 100vw;">
          <h5 class="text-center">Perolehan Nilai</h5>
          <nilai-chart :height="240" />
        </div>
      </div>
   
		  <div class="row">
        <h6>Kalkulasi IPK : {{ ipk }} / {{ persen }}%
		    <br>Dari skala 4.00</h6>
        <q-progress :percentage="persen" stripe animate style="height: 24px" />
		  </div> 
    </div>
	</div>
</template>

<script>
import {
  QIcon,
  QProgress,
  QField
} from 'quasar'
import KhsChart from './KhsChart.js'
import NilaiChart from './NilaiChart.js'
import axios from 'axios'

export default {
  components: {
    'khs-chart': KhsChart,
    'nilai-chart': NilaiChart,
    QIcon,
    QProgress,
    QField
  },
  data () {
    return {
      ipk: 0,
      persen: 0
    }
  },
  mounted () {
    this.ambilKRS()
  },
  methods: {
    ambilKRS () {
      setTimeout(() => {
        axios.get('tsKhs').then((response) => {
          this.ipk = response.data.ipk
          this.persen = response.data.persen
        })
      }, 1500)
    }
  }
}
</script>

<style>
.khsdash
  .ChartKS {
    padding-bottom: 10px;
    margin-bottom: 10px;
    box-shadow: 0px 0px 3px rgba(25, 25, 25, 0.27);
    border-radius: 2px;
  }
  .ChartKS H5 {
    margin-top: 0;
    padding: 10px 0;
  }
  .ChartPN {
    padding-bottom: 10px;
    box-shadow: 0px 0px 3px rgba(25, 25, 25, 0.27);
    border-radius: 2px;
  }

  .ChartKS PN {
    margin-top: 0;
    padding: 10px 0;
  }
</style>
