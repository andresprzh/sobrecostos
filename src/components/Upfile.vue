<template>
  <v-container grid-list-md>
    
    <h1 class="font-weight-black font-italic text-xs-center" >Subir archivo sobrecostos</h1>
    <v-form  >

      <v-layout row wrap class="file-upload"  >
        
        <v-flex xs12 md12 v-show="true" class="file-upload-main"  justify-center>

             <input type="file" name="file" id="file"
             :disabled="loading"
             @change="processFile($event)"
             />
             
            <label for="file" >
                <v-icon v-if="!file.name">fa-upload</v-icon>
                <v-progress-circular v-else-if="loading"
                  indeterminate
                  color="primary"
                ></v-progress-circular>
                <span v-else>{{file.name}}</span>
               
            </label>
        </v-flex>
        <v-flex xs12>
        <v-alert
          :value="!valid"
          type="error"
        >
          Seleccionar un archivo.
        </v-alert>
        </v-flex>
        <v-btn block color="secondary"
        :disabled="!valid || loading"
        @click="submit"
        >
          submit
        </v-btn>
      </v-layout>
        
    </v-form>
    <!-- INPUTS FORMULARIO -->
  </v-container>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import App from '@/App.vue';

@Component
export default class Upfile extends App {
  /*===========================================================================================================
                                          ATRIBUTOS
  =============================================================================================================*/
  protected valid: boolean = true;
  protected loading: boolean = false;
  protected file: any = {};
  private processFile(event: any) {
    if ( event.target.files[0]) {
      this.file = event.target.files[0];
      this.valid = true;
    } else {
      if (!this.file.name) {
        this.valid = false;
      }
    }
  }

  private submit(event: any) {
    if (this.file.name) {
      this.loading = true;
      const formData = new FormData();
      formData.append('archivo', this.file);
      const path = this.path+'sobrecostos';
      this.axios
        .post(path, formData, {
          headers: { 'Content-Type': 'multipart/form-data' },
        })
        .then((res) => {
          this.loading = false;
          if (res.data) {
            alert(res.data.contenido);
          }
        })
        .catch((error) => {
          this.loading = false;
        });
    } else {
      this.valid = false;
    }
  }

}
</script>