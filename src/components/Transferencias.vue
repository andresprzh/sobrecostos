<template>
  <v-container grid-list-md>
    
    <h1 class="font-weight-black font-italic text-xs-center" >Solicitud de transferencias</h1>
    <v-form>

      <v-layout row wrap>

        <v-flex xs12 > 

          <v-select 
            ref="menu"
            :items="plaremi.items"
            v-model="plaremi.dato"
            :label="plaremi.titulo"
            item-text="factura"
            item-value="factura"
            @change="selplaremi"
            required
          ></v-select>

        </v-flex>

      </v-layout>


    </v-form>
    <router-view></router-view>

    <!-- INPUTS FORMULARIO -->
  </v-container>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import App from '@/App.vue';

@Component
export default class Transferencia extends Vue {
  
  /*===========================================================================================================
                                          ATRIBUTOS
  =============================================================================================================*/
  private path:string="http://192.168.0.49/sobrecostos/api/";
  
  
  private plaremi={
    id: 'plaremi',
    titulo: 'Remision',
    dato: '',
    items: [],
  };
  
  /*===========================================================================================================
                                          METODOS
  =============================================================================================================*/
  constructor() {
    super();

    const path = this.path+'plaremi';
    this.axios
      .get(path, {
        params: {
          dato: 'all'
        }
      })
      .then(res => {
        if(res){
          
          this.plaremi.items=res.data;
        }

      })
      .catch(error => {
        // eslint-disable-next-line
        console.error(error);
      });
  }


  private selplaremi(){

    this.$router.push({ name: 'Tabla', params: { id: this.plaremi.dato }})

  }
  
}

</script>