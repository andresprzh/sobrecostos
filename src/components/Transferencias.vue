<template>
  <v-container grid-list-md>

      <v-form v-if="show_form" @submit="submit"  ref="form" v-model="valid" lazy-validation>
        <div class="form">
          <v-card row wrap  v-for="(itemsede,index) in items" :key="index">

            <v-card-title>
              Transferencia {{itemsede.items[0].origensede}}
              <v-spacer></v-spacer>
              <v-text-field
                label="Numero de Transferencia"
                v-model="itemsede.no_transferencia"
                :rules="[(v) => !!v || 'Por favor digite el número de transferencia',(v) => ValNoTrans(itemsede.no_transferencia,index) || 'Numero de transferencia Repetido']"
                required
              ></v-text-field>
            </v-card-title>
            <v-data-table
              :headers="headers"
              :items="itemsede.items"
              class="elevation-1">
              
              <template slot="items" slot-scope="props">
                <td class="text-xs-left">{{props.item.item}}</td>
                <td class="text-xs-left">{{props.item.sobrante}}</td>
                <td class="text-xs-left">{{props.item.solicitado}}</td>
                <td class="text-xs-left">
                  <v-text-field
                    v-model="props.item.pedido"
                    label="Pedidos"
                    :rules="[(v) => !!v || 'Por favor digite el una cantidad',v => (v && v <=props.item.sobrante ) || 'Cantidad maxima = '+props.item.sobrante]"
                    required
                  ></v-text-field>
                </td>
              </template>
            </v-data-table>

            
          </v-card >
        </div>
        
        <div class="text-xs-right pt-2" >

          <v-btn type="submit" color="secondary"  >
          
            <v-icon>fa-save</v-icon>
            <span>Guardar</span>

          </v-btn>

        </div>
        
      </v-form>

  </v-container>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import App from '@/App.vue';

@Component
export default class Transferencia extends App {
  
  /*===========================================================================================================
                                          ATRIBUTOS
  =============================================================================================================*/
  private items: object[] = []; 
  private id:string='';
  private show_form:boolean=false;
  private valid:boolean=true;
  private plaremi={
    id: 'plaremi',
    titulo: 'Remision',
    dato: '',
    items: [],
  };
  
  private headers = [ 
    { text: 'Item', value: 'item' },
    { text: 'Sobrantes ', value: 'sob' },
    { text: 'Solicitados', value: 'col' },
    { text: 'Pedido', value: 'perfil' },
  ];

  private transfRules= [
          (v:any) => !!v || 'por favor digite el número de transferencia',
  ];


  /*===========================================================================================================
                                          METODOS
  =============================================================================================================*/
  private created() {
    this.id = this.$route.params.id; 
    const path = this.path+'transferencia';
    this.axios
      .get(path, {
        params: {
          encargado: localStorage.id,
          plaremi:this.id,
        }
      })
      .then(res => {
        
        if(res.data){
          
          this.items=res.data;
          this.show_form=true;

        }

      })
      .catch(error => {
        // eslint-disable-next-line
        console.error(error);
    });
  }

  get computedForm ():any {
    return this.$refs.form as Vue
  }

  get computedNoTrans () {
    let key:string;
    let newArray = new Array;;
    let items:any = this.items;
    for (key in items) {
      newArray.push(items[key]['no_transferencia']);
    }
    return newArray;
  }

  // verifica si el numero de transferencia es unico en todas las entradas
  private ValNoTrans(val:string,index:string):boolean{
    let key:string;
    let items:any = this.items;
    for (key in items) {
      if(key!=index && items[key]['no_transferencia']==val){
        return false;
      }
    }
    return true;
  }

  private submit(){
    
    if (this.computedForm.validate()) {
      let formData = new FormData();
      let items:string=JSON.stringify(this.items);
      formData.append('items', items);
      formData.append('encargado', localStorage.id);
      formData.append('update', 'true');

      const path = this.path+'transferencia';
      this.axios
        .post(path, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        })
        .then(res => {
          if(res.data){
            this.$router.replace({ name: 'Remisiones' });
          }else{
            alert("Error al generar solicitud de transferencia")
          }
        })
        .catch(error => {
          console.error(error);
      });
    }

  }
  
}

</script>