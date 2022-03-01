<template>
<div class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <router-link to="/">اسم الموقع</router-link>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <form action="../../index3.html" method="post">
                <p v-if="serverside_error != null" class="text-danger text-center">{{serverside_error}}</p>
                <p v-if="message != null" class="text-success text-center">{{message}}</p>
                <div class="input-group mb-3">
                    <input v-model="form.email"
                          :class="(v$.form.email.$error) ? 'is-invalid' : '' "
                          type="email" class="form-control" placeholder="Email">

                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback" v-if="v$.form.email.$error">{{ v$.form.email.$errors[0].$message }}</div>

                </div>
                <div class="row">
                    <!-- /.col -->
                    <div class="col-12">
                        <button @click.prevent="forgot_password()" class="btn btn-primary btn-block">send email</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            </div>
            <!-- /.login-card-body -->
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

      axios({url: '/admin/forgotpassword', method: 'post', data: fd})
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
