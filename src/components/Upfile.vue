<template>
  <v-container grid-list-md>
    
    <h1 class="font-weight-black font-italic text-xs-center" >Subir archivo sobrecostos</h1>
    <v-form class="file-upload"  >
      <v-layout row wrap >
        
        <v-flex xs12 md12 v-show="true" class="file-upload-main"  justify-center>

             <input type="file" name="file" id="file"
             
             @change="processFile($event)"
             />
             
            <label for="file" >
                <v-icon v-if="file.name==null">fa-upload</v-icon>
               {{file.name}}
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
        :disabled="!valid"
        @click="submit"
        >
          submit
        </v-btn>
      </v-layout>
        
    </v-form>
    <!-- INPUTS FORMULARIO -->
  </v-container>
</template>
<style scoped>
  .file-upload .file-upload-main{
  
  display: flex;
  align-items: center;
  position: relative;
  min-height: 100px;
  border: 2px dashed green;
  
}
.file-upload .file-upload-main:hover p{
    font-size:20px;
}
.file-upload .file-upload-main:hover p{
    font-size:25px;
}

.file-upload input[type=file]{
    
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
  width: 100%;
  height: 100%;
  cursor: pointer;
}
.file-upload .file-upload-res{
    width: 100%;
    text-align: center;
    color:gray;
}

</style>
<script lang="ts">
import { Component, Vue } from "vue-property-decorator";

@Component
export default class Upfile extends Vue {
  
  /*===========================================================================================================
                                          ATRIBUTOS
  =============================================================================================================*/
  private valid = true;
  private file: any = {};


  private processFile(event: any) {
    // alert("change");
    if( event.target.files[0]){
      this.file = event.target.files[0];
      this.valid=true;
    }else{
      if(!this.file.name){
        this.valid=false;
      }
      
    }
    
  }

  private submit(event:any){
    
    if(this.file.name){
      let formData = new FormData();
        formData.append("archivo", this.file);
        // const valid: boolean = true;
        const path = "http://localhost/sobrecosto/sobrecostos";

        this.axios
          .post(path, formData, {
            headers: { "Content-Type": "multipart/form-data" }
          })
          .then(res => {
            // this.msg = res.data;
            console.log(res.data);
            
          })
          .catch(error => {
            // eslint-disable-next-linen
            console.error(error);
          });
    }else{
      this.valid=false;
    }
  }

}
</script>