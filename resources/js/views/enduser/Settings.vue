<template>
  <div class="enduser">
        <div class="overlay">
            <div class="box">

                <h2 class="box-title">settings</h2>
                <p class="box-p">edit your info</p>
                <div class="mb-3">
                    <input  type="text" class="form-control form-control-lg" placeholder="name"
                            v-model="form.name"
                            :class="(v$.form.name.$error || serverside_error.name) ? 'is-invalid' : '' ">
                    <div class="invalid-feedback" v-if="v$.form.name.$error">{{ v$.form.name.$errors[0].$message }}</div>
                    <div class="invalid-feedback" v-if="serverside_error.name">{{ serverside_error.name[0] }}</div>
                </div>
                <div class="mb-3">
                    <input  type="email" class="form-control form-control-lg" placeholder="email"
                            v-model="form.email"
                            :class="(v$.form.email.$error || serverside_error.email) ? 'is-invalid' : '' ">
                    <div class="invalid-feedback" v-if="v$.form.email.$error">{{ v$.form.email.$errors[0].$message }}</div>
                    <div class="invalid-feedback" v-if="serverside_error.email">{{ serverside_error.email[0] }}</div>
                </div>
                <div class="mb-3">
                    <input  type="password" class="form-control form-control-lg" placeholder="password"
                            v-model="form.password"
                            :class="(v$.form.password.$error || serverside_error.password) ? 'is-invalid' : '' ">
                    <div class="invalid-feedback" v-if="v$.form.password.$error">{{ v$.form.password.$errors[0].$message }}</div>
                    <div class="invalid-feedback" v-if="serverside_error.password">{{ serverside_error.password[0] }}</div>
                </div>
                <div class="mb-3">
                    <input  type="password" class="form-control form-control-lg" placeholder="confirm password"
                            v-model="form.password_confirmation"
                            :class="(v$.form.password_confirmation.$error || serverside_error.password_confirmation) ? 'is-invalid' : '' ">
                    <div class="invalid-feedback" v-if="v$.form.password_confirmation.$error">{{ v$.form.password_confirmation.$errors[0].$message }}</div>
                    <div class="invalid-feedback" v-if="serverside_error.password_confirmation">{{ serverside_error.password_confirmation[0] }}</div>
                </div>
                <div class="mb-3">
                    <button @click="update()" class="btn btn-lg btn-block btn-primary">update</button>
                </div>
                <router-link  to="/home" class="d-block text-center">back to home</router-link>

            </div>
        </div>
  </div>
</template>

<script>
import { minLength, email, required, sameAs} from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'

export default {
  name: 'update',
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
  data() {
      return{
        form:{
          name: null,
          email: null,
          password: null,
          password_confirmation: null
        },
        serverside_error: {}
      }
  },
  methods:{
        read:function(){
            this.form = this.$store.state.auther
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

            axios({ url: '/profile', method: 'post', data: fd})
            .then(response=> {
              if(response.data.statusText == "updated"){
                  Vue.$toast.open({
                    message: response.data.message,
                    type: 'info',
                });
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
