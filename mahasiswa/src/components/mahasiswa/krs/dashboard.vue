<template>
	<div class="layout-padding row justify-center">
		<div style="width: 100%; max-width: 100vw;">
        <div class="row">
  		  	<div class="Chart" style="width: 100%; max-width: 100vw;">
  		    	<h5 class="text-center">SKS Semester</h5>
  		     	<krs-chart :height="240" />
  		    </div>
        </div>
		
		    <div class="row">
		      <h6 clas="col-6">Ready Wisuda : {{ persen }}%</h6>
              <q-progress :percentage="persen" stripe animate style="height: 24px" />
		    </div>
		    <br>
		    <div class="row " >
		        <div class="col-6">
                  <p class="caption text-center">Total SKS</p>
                  <div class="row justify-center">
                      <q-knob
                        v-model="tkrs"
                        :min="0"
                        :max="145"
                        color="primary"
                        readonly
                      >
                         {{ tkrs }}
                      </q-knob>	            
		        </div>
		        </div>
		        <div class="col-6">
                  <p class="caption text-center">Minimal SKS</p>
                  <div class="row justify-center">
                      <q-knob
                        v-model="minimal"
                        :min="0"
                        :max="145"
                        color="secondary"
                        readonly
                      >
                         {{ minimal }}
                      </q-knob>		            
		          </div>		        
		        </div>		        
		    </div>
	  </div>
	</div>
</template>

<script>
import {
  QKnob,
  QIcon,
  QProgress,
  QInnerLoading,
  QSpinnerGears,
  QField
} from 'quasar'
import KrsChart from './KrsChart.js'
import axios from 'axios'

export default {
  components: {
    'krs-chart': KrsChart,
    QKnob,
    QIcon,
    QProgress,
    QInnerLoading,
    QSpinnerGears,
    QField
  },
  data () {
    return {
      tkrs: 0,
      persen: 0,
      min: 0,
      max: 145,
      minimal: 145
    }
  },
  mounted () {
    this.ambilKRS()
  },
  methods: {
    ambilKRS () {
      setTimeout(() => {
        axios.get('tSks').then((response) => {
          this.tkrs = response.data.krs
          this.persen = response.data.total
        })
      }, 1500)
    }
  }
}
</script>

<style>
.Chart {
  box-shadow: 0px 0px 3px rgba(25, 25, 25, 0.27);
  border-radius: 2px;
}

.Chart H5 {
  margin-top: 0;
  padding: 10px 0;
}
</style>
