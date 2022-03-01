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
        <div class="mb-3">
            <label class="form-label">email</label>
            <input
              v-model="form.email"
              :class="(v$.form.email.$error || serverside_error.email) ? 'is-invalid' : '' "
              type="text" class="form-control" placeholder="email ...">
              <div class="invalid-feedback" v-if="v$.form.email.$error">{{ v$.form.email.$errors[0].$message }}</div>
              <div class="invalid-feedback" v-if="serverside_error.email">{{ serverside_error.email[0] }}</div>
        </div>
        <div class="mb-3">
            <label class="form-label">password</label>
            <input
              v-model="form.password"
              :class="(v$.form.password.$error || serverside_error.password) ? 'is-invalid' : '' "
              type="password" class="form-control" placeholder="password ...">
              <div class="invalid-feedback" v-if="v$.form.password.$error">{{ v$.form.password.$errors[0].$message }}</div>
              <div class="invalid-feedback" v-if="serverside_error.password">{{ serverside_error.password[0] }}</div>
        </div>
        <div class="mb-3">
            <label class="form-label">password confirm</label>
            <input
              v-model="form.password_confirmation"
              :class="(v$.form.password_confirmation.$error || serverside_error.password_confirmation) ? 'is-invalid' : '' "
              type="password" class="form-control" placeholder="password ...">
              <div class="invalid-feedback" v-if="v$.form.password_confirmation.$error">{{ v$.form.password_confirmation.$errors[0].$message }}</div>
              <div class="invalid-feedback" v-if="serverside_error.password_confirmation">{{ serverside_error.password_confirmation[0] }}</div>
        </div>

        <div class="mb-3">
                <div class="row">
                <div class="col-9 col-sm-10">
                    <div class="form-group">
                        <label for="exampleInputFile">photo</label>
                        <div 
                            :class="(serverside_error.photo) ? 'is-invalid' : '' "
                            class="input-group">
                            <div class="custom-file">
                                <input 
                                    @change="processFile($event, 'photo')"
                                    :class="(serverside_error.photo) ? 'is-invalid' : '' "
                                    type="file" class="custom-file-input" id="exampleInputFile">
                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                            </div>
                        </div>
                        <div class="invalid-feedback" v-if="serverside_error.photo">{{ serverside_error.photo[0] }}</div>
                    </div>
                </div>
                <div class="col-3 col-sm-2">
                    <img class="img_preview" :src="form.photo_path" alt="">
                </div>
                </div>
            </div>

        <button type="button" class="btn btn-primary" @click="update()">save change</button>
    </div>
  </div>
</template>

<script>
import { minLength, email, required, sameAs} from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'

export default {
  name: 'editadmin',
    setup: () => ({ v$: useVuelidate() }),
    validations () {
        return {
            form: {
                name: { required },
                email: { required, email },
                password: { minLength: minLength(6) },
                password_confirmation: { minLength: minLength(6), sameAsPassword: sameAs(this.form.password)},
            }
        }
    },
    data(){
        return {
            form: {
              name: null,
              email: null,
              password: null,
              password_confirmation: null,
              photo: null,
              photo_path: "/images/default.png"
            },
            serverside_error: {},
        }
    },
    methods:{
        processFile(event) {
          var file = event.target.files[0] 
          this.form.photo = file
          this.form.photo_path = URL.createObjectURL(file);
        },
        read:function(){
            this.form = this.$store.state.adminAuther
        },
        async update(){
            //front end validation
            const result = await this.v$.form.$validate()
            if (!result) { return}

            //form data
            var fd = new FormData()
            if(this.form.name) { fd.append('name',this.form.name) }
            if(this.form.email) { fd.append('email',this.form.email) }
            if(this.form.password) { fd.append('password',this.form.password) }
            if(this.form.password_confirmation) { fd.append('password_confirmation',this.form.password_confirmation) }
            if(typeof(this.form.photo) == "object"){
              fd.append('photo', this.form.photo)
            }

            axios({ url: '/adminprofile/', method: 'post', data: fd})
            .then(response=> {
              if(response.data.statusText == "updated"){
                this.$router.push({
                  name: 'admins', 
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