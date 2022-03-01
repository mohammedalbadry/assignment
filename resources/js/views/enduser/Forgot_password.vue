<template>
  <div class="enduser">
        <div class="overlay">
            <div class="box">
                <h2 class="box-title">forget password</h2>
                <p class="box-p">here you can easily retrieve a new password.</p>
                <div class="mb-3">
                    <h2 v-if="message != null" class="text-success text-center">{{message}}</h2>
                    <input  type="email" class="form-control form-control-lg" placeholder="email"
                            v-model="form.email"
                            :class="(v$.form.email.$error || serverside_error != null) ? 'is-invalid' : '' ">
                    <div class="invalid-feedback" v-if="v$.form.email.$error">{{ v$.form.email.$errors[0].$message }}</div>
                    <div class="invalid-feedback" v-if="serverside_error !== null">{{ serverside_error }}</div>
                </div>
                <div class="mb-3">
                    <button @click="forgot_password()" class="btn btn-lg btn-block btn-primary">forget password</button>
                </div>
            </div>
        </div>
  </div>
</template>

<script>
import { email, required } from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'

export default {
    name: 'login',
    setup: () => ({ v$: useVuelidate() }),
    validations () {
        return {
            form: {
                email: { required, email },
            },
        }
    },
    data(){
        return {
            form:{
                email: null,
            },
            serverside_error: null,
            message:null
        }
    },
    methods:{

    async forgot_password(){
      //front end validation
      const result = await this.v$.form.$validate()
      if (!result) { return}

      //request from data
      var fd = new FormData()
      if(this.form.email) { fd.append('email',this.form.email) }

      this.message = "processing"

      axios({url: '/auth/forgotpassword', method: 'post', data: fd})
      .then(res=> {

          if(res.data.statusText == "error"){
                this.serverside_error = res.data.data.error
                this.message = null
          } else if(res.data.statusText == "sent") {
              this.serverside_error = null
              this.message = res.data.message
          }
        
      })
      .catch( error => { /*console.log(error)*/ });
    }
 
    }
}
</script>
