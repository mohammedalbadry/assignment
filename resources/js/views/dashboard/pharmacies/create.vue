<template>
  <div class="card card-primary card-outline">
    <div class="card-body box-profile">
        
        <div class="mb-3">
            <label class="form-label">name</label>
            <input
              v-model="form.name"
              :class="(v$.form.name.$error || serverside_error.name) ? 'is-invalid' : '' "
              type="text" class="form-control" placeholder="name ...">
              <div class="invalid-feedback" v-if="v$.form.name.$error">{{ v$.form.name.$errors[0].$message }}</div>
              <div class="invalid-feedback" v-if="serverside_error.name">{{ serverside_error.name[0] }}</div>
        </div>

        <button type="button" class="btn btn-primary" @click="create()">save change</button>
    </div>
  </div>
</template>

<script>
import { required} from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'

export default {
    name: 'createpharmacy',
    setup: () => ({ v$: useVuelidate() }),
    validations () {
        return {
            form: {
                name: { required },
            }
        }
    },
    data(){
        return {
            form: {
              name: null,
            },
            serverside_error:{}
        }
    },
    methods:{
        async create (){
            //front end validation
            const result = await this.v$.form.$validate()
            if (!result) { return}

            //form data
            var fd = new FormData()
            if(this.form.name) { fd.append('name',this.form.name) }

            axios({url: 'pharmacies', method: 'post', data: fd})
            .then(response=> {
              if(response.data.statusText == "created"){
                this.$router.push({
                  name: 'pharmacies', 
                  params: { message: response.data.message }})
              }
              if(response.data.statusText == "error"){
                this.serverside_error = response.data.data.errors
              }
            }).catch( error => {/*console.log(error)*/});

        },
    },
}
</script>

<style>
.img{
  width: 80px;
  overflow: hidden;
  border: 1px solid #007bff;
  padding: 2px;
  margin: 0 auto 10px;
}
.img img{
  width: 100%;
}
.img_preview{
  width: 100%;
}
</style>