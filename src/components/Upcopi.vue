<template>
  <v-container grid-list-md>
    
    <h1 class="display-3 text-xs-center" >Cargar plano copidrogas</h1>
    <small class="font-italic">*Una ves cargado el archivo se mostraran los productos que tienen sobrestock en otras sedes</small>
    <!-- **************************************************************************************************************** 
                                            INPUTS FORMULARIO
    **************************************************************************************************************** -->
    <v-form ref="form"  :v-model="valid" >
    
      <v-layout row wrap>

        <v-flex xs12 row wrap class="file-upload"  >
          
          <v-flex xs12 v-show="true" class="file-upload-main"  justify-center>

              <input type="file" name="file" id="file"  @change="processFile($event)"/>
              <label for="file" >

                <v-icon v-if="file.name==null">fa-upload</v-icon>
                {{file.name}}

              </label>

          </v-flex>

          <v-flex xs12>

            <v-alert :value="!valid && !file.name" type="error">Seleccionar un archivo.</v-alert>

          </v-flex>

        </v-flex >

        <v-btn block color="secondary" @click="submit_file">Subir</v-btn>

      </v-layout>

    </v-form> 

  </v-container>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import App from '@/App.vue';

@Component
export default class Upcopi extends App {
  /*===========================================================================================================
                                          ATRIBUTOS
  =============================================================================================================*/
  
  private file: any = {};
  private valid:boolean= true;
  protected loading:boolean=false;
  
  private filename: string = "Subir archivo";

  /*===========================================================================================================
                                          METODOS
  =============================================================================================================*/
  constructor() {
    super();
  }

  private processFile(event: any) {

    if( event.target.files[0]){
      this.file = event.target.files[0];
      this.valid=true;
    }
    
  }

  private submit_file(event: any) {
     
    if (this.file.name) {
        this.valid=true;
        let formData = new FormData();
        formData.append("archivo", this.file);
        formData.append("sede", localStorage.sede);

        const path = this.path+"copiupload";

        this.axios
          .post(path, formData, {
            headers: { "Content-Type": "multipart/form-data" }
          })
          .then(res => {

            if (res.data) {
              if(res.data.estado){
                this.$router.replace({ name: 'Tabla', params: { id: res.data.contenido }});
              }else{
                alert(res.data.contenido);
              }
            } else {
              alert("error al subir el arcivo");
            }
          })
          .catch(error => {
            console.error(error);
          });
    }else{
      this.valid=false
    }
    
  }

  
  
}
</script>