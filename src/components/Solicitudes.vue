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
              <v-btn color="error"  flat icon title="Eliminar" @click="eliminartrans(itemsede.id_transferencia)">
          
                <v-icon>fa-times</v-icon>

              </v-btn>

            </v-card-title>
            <v-data-table
              :headers="headers"
              :items="itemsede.items"
              class="elevation-1">
              
              <template slot="items" slot-scope="props">
                <td class="text-xs-left">{{props.item.descripcion}}</td>
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
                <td class="text-xs-left">
                  <v-btn color="grey"  flat icon title="Eliminar" @click="eliminaritem(props.item.item,itemsede.id_transferencia)" >
          
                    <v-icon>fa-times</v-icon>

                  </v-btn>
                </td>
              </template>
            </v-data-table>

            
          </v-card >
        </div>
        
        <div class="text-xs-right pt-2" >

          <v-btn type="submit" color="secondary"  >
          
            <v-icon class="mx-2">fa-save</v-icon>
            <span>Guardar</span>

          </v-btn>

        </div>
        
      </v-form>

      <v-snackbar v-model="snack.show" :timeout="3000" :color="snack.color">
        {{ snack.text }}
        <v-btn flat @click="snack.show = false">Cerrar</v-btn>
      </v-snackbar>
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

  private snack={
    color: '',
    text:'',
    show:false,
  };

  /*===========================================================================================================
                                          METODOS
  =============================================================================================================*/
  private created() {
    this.getitems();
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

  private getitems(){
    this.id = this.$route.params.id; 
    const path = this.path+'solicitudes';
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

        }else{
          this.items=[];
          this.show_form=false;
          this.snack.text="no hay solicitudes de transferencia";
          this.snack.color= String(this.$vuetify.theme.error);
          this.snack.show=true;
        }

      })
      .catch(error => {
        // eslint-disable-next-line
        console.error(error);
    });
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
      this.$swal({
      title: '¿Crear transferencia?',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: String(this.$vuetify.theme.primary),
      cancelButtonColor: String(this.$vuetify.theme.error),
      confirmButtonText: 'Si',
      cancelButtonText: 'No'
    }).then((result) => {
        if (result.value) {
          let formData = new FormData();
          let items:string=JSON.stringify(this.items);
          formData.append('items', items);
          formData.append('encargado', localStorage.id);
          formData.append('update', 'true');

          const path = this.path+'solicitudes';
          
          this.axios
            .post(path, formData, {
              headers: { 'Content-Type': 'multipart/form-data' }
            })
            .then(res => {
              if(res.data){
                this.$swal({
                  type: 'success',
                  title: 'Solicitud de transferencia creada',
                }).then((result)=>{
                  this.$router.replace({ name: 'Remisiones' });
                });
              }else{
                this.$swal({
                  type: 'error',
                  title: 'Error al crear transferencia',
                });
              }
            })
            .catch(error => {
              console.error(error);
          });
        }
      });
    }

  }

  private eliminartrans(id_transferencia:string){
    this.$swal({
      title:'¿Eliminar solicitud?',
      type:'warning',
      showCancelButton: true,
      confirmButtonColor: String(this.$vuetify.theme.primary),
      cancelButtonColor: String(this.$vuetify.theme.error),
      confirmButtonText: 'Si',
      cancelButtonText: 'No',
    }).then((result)=>{
      if (result.value) {

        let formData=new FormData();
        formData.append('delete','true');
        formData.append('id_transferencia',id_transferencia);
        const path = this.path+'solicitudes';
        this.axios
          .post(path,formData,{
             headers: { 'Content-Type': 'multipart/form-data' }
          })
          .then((res)=>{
            console.log(res.data);
            if (res.data) {
              this.getitems();
            }else{
              this.$swal({
                type: 'error',
                title: 'Error',
              });
            }
        });
      }
    });
  }

  private eliminaritem(item:string,id_transferencia:string){
    this.$swal({
      title:'¿Eliminar item de la solicitud?',
      type:'warning',
      showCancelButton: true,
      confirmButtonColor: String(this.$vuetify.theme.primary),
      cancelButtonColor: String(this.$vuetify.theme.error),
      confirmButtonText: 'Si',
      cancelButtonText: 'No'
    }).then((result)=>{
      if (result.value) {

        let formData=new FormData();
        formData.append('delete','true');
        formData.append('id_transferencia',id_transferencia);
        formData.append('item',item);
        const path = this.path+'solicitudes';
        this.axios
          .post(path,formData,{
             headers: { 'Content-Type': 'multipart/form-data' }
          })
          .then((res)=>{
            console.log(res.data);
            if (res.data) {
              this.getitems();
            }else{
              this.$swal({
                type: 'error',
                title: 'Error',
              });
            }
        });
      }
    });
  }
  
}

</script>