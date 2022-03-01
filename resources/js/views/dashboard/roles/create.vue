<template>
    <div class="card card-outline card-primary">
        <!-- form start -->
        <div class="card-body">

            <div class="mb-3">
                <label class="form-label">name</label>
                <input
                    v-model="form.name"
                    :class="(v$.form.name.$error || serverside_error.name) ? 'is-invalid' : '' "
                    type="text" class="form-control" placeholder="name ...">
                <div class="invalid-feedback" v-if="v$.form.name.$error">{{ v$.form.name.$errors[0].$message }}</div>
                <div class="invalid-feedback" v-if="serverside_error.name">{{ serverside_error.name[0] }}</div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">description (optional)</label>
                <textarea
                    v-model="form.description"
                    :class="(serverside_error.description) ? 'is-invalid' : '' "
                    class="form-control" placeholder="description ..."></textarea>
                <div class="invalid-feedback" v-if="serverside_error.description">{{ serverside_error.description[0] }}</div>
            </div>

              <div class="mb-3">
                  <label class="form-label">selected some permissions</label>
                  <div class="row">
                    <div class="col-6 col-md-2" v-for="(item, index) in permission" :key="index">
                        <div class="role-btn m-2"> {{item.display_name}} 
                              <input class="form-check-input" 
                                    :checked="selected_permission.includes(item.id)"
                                    @click="addPermission(item.id)" type="checkbox">
                              <div class="checked">
                                <span class="icon"><fa icon="check-circle"/></span>
                              </div>
                        </div>
                    </div>
                  </div>
              </div>
              <div class="alert alert-danger" v-if="v$.selected_permission.$error">{{ v$.selected_permission.$errors[0].$message }}</div>
              <div class="alert alert-danger" v-if="serverside_error.permissions">{{ serverside_error.permissions[0] }}</div>

            <button type="button" class="btn btn-primary" @click="create()">save change</button>

        </div>
        <!-- /.card-body -->


    </div>
</template>

<script>
import { minLength, required} from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'

export default {
    name: 'role_create',
    setup: () => ({ v$: useVuelidate() }),
    validations () {
        return {
            form: {
                name: { minLength: minLength(2), required },
            },
            selected_permission: {required}
        }
    },
    data(){
        return {
            form: {
              name:null,
              description:null
            },
            permission: {},
            selected_permission:[],
            serverside_error: {},
        }
    },
    methods:{
        read(){
          axios({ url: '/permission/', method: 'get', })
          .then(response=> {
              this.permission = response.data.data
          })
          .catch( error => {/*console.log(error)*/});
        },
        addPermission(id){
          if(this.selected_permission.includes(id)){
              var index = this.selected_permission.indexOf(id);
              this.selected_permission.splice(index, 1);
          } else {
              this.selected_permission.push(id)
          }
        },
        async create (){
            //front end validation
            var result = await this.v$.form.$validate()
                result = await this.v$.form.$validate()
            if (!result) { return}

            //form data
            var fd = new FormData()
            if(this.form.name) { fd.append('name',this.form.name) }
            if(this.form.description) { fd.append('description',this.form.description) }
            if(this.selected_permission.length) { fd.append('permissions[]',this.selected_permission) }

            axios({url: 'roles', method: 'post', data: fd})
            .then(response=> {
              if(response.data.statusText == "created"){
                this.$router.push({
                  name: 'roles', 
                  params: { message: response.data.message }})
              }
              if(response.data.statusText == "error"){
                this.serverside_error = response.data.data.errors
              }
            }).catch( error => {/*console.log(error)*/});

        },
    },
    mounted(){
      this.read()
    }
}
</script>

<style scoped>
.role-btn{
  position: relative;
  padding: 8px 12px;
  border: none;
  font-size: 1.2em;
  background-color: #343a40;
  cursor: pointer;
  color: #ffffff;
}
.role-btn input{
  z-index: 100;
  width: 100%;
  height: 100%;
  position: absolute;
  top: -4px;
  left: 20px;
  cursor: pointer;
  opacity: 0;
}
.role-btn .checked{
  z-index: 50;
  position: absolute;
  box-sizing: border-box;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  box-shadow: 2px 2px 4px 2px #d63031;
  border-radius: 5px;
  transition: all .2s ease;
}
.role-btn .checked .icon{
    z-index: 50;
    position: absolute;
    box-sizing: border-box;
    top: 8px;
    right: 10px;
    display: block;
    color: #00b894;
    transition: all .2s ease;
    opacity: 0;
}
.role-btn input:checked + .checked{
    box-shadow: 2px 2px 4px 2px #00b894;
}
.role-btn input:checked + .checked .icon{
    opacity: 1;
}
</style>