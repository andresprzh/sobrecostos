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
export default class Remisiones extends App {
  
  /*===========================================================================================================
                                          ATRIBUTOS
  =============================================================================================================*/
  
  protected plaremi={
    id: 'plaremi',
    titulo: 'Remision',
    dato: '',
    items: [],
  };
  
  /*===========================================================================================================
                                          METODOS
  =============================================================================================================*/
  private created() {

    const path = this.path+'plaremi';
    this.axios
      .get(path, {
        params: {
          sede: localStorage.sede
        }
      })
      .then(res => {
        
        if(res.data){
          
          this.plaremi.items=res.data;
        }

      })
      .catch(error => {
        // eslint-disable-next-line
        console.error(error);
    });
  }


  protected selplaremi(){

    const path = this.path+'solicitudes';
    this.axios
      .get(path, {
        params: {
          encargado: localStorage.id,
          plaremi:this.plaremi.dato,
        }
      })
      .then(res => {
        if(res.data){
          this.$router.push({ name: 'Solicitudes', params: { id: this.plaremi.dato }});
        }else{
          this.$router.push({ name: 'Tabla', params: { id: this.plaremi.dato }});
        }

      })
      .catch(error => {
        // eslint-disable-next-line
        console.error(error);
    });
   

  }
  
}

</script>