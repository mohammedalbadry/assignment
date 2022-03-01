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
                <div class="input-group mb-3">
                    <input v-model="form.password"
                           :class="(v$.form.password.$error) ? 'is-invalid' : '' "
                           type="password" class="form-control" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback" v-if="v$.form.password.$error">{{ v$.form.password.$errors[0].$message }}</div>
                </div>
                <div class="input-group mb-3">
                    <input v-model="form.password_confirmation"
                           :class="(v$.form.password_confirmation.$error) ? 'is-invalid' : '' "
                           type="password" class="form-control" placeholder="password confirm">
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback" v-if="v$.form.password_confirmation.$error">{{ v$.form.password_confirmation.$errors[0].$message }}</div>
                </div>
                <div class="row">
                    <!-- /.col -->
                    <div class="col-12">
                        <button @click.prevent="resetPassword()" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <p class="mb-1">
                    <router-link  to="/admin/forgot_password">I forgot my password</router-link>
            </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</div>
</template>


<script>
import { minLength, email, required, sameAs} from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'

export default {
  name: 'reset-password',
  setup: () => ({ v$: useVuelidate() }),
  validations () {
    return {
        form: {
            password: { minLength: minLength(6), required },
            password_confirmation: { required, minLength: minLength(6), sameAsPassword: sameAs(this.form.password)},
        }
    }
  },
  data() {
      return{
        form:{
          password: null,
          password_confirmation: null
        },
        serverside_error: null
      }
  },
  methods:{
    async resetPassword(){
      //front end validation
      const result = await this.v$.form.$validate()
      if (!result) { return}

      //request from data
      var fd = new FormData()
      if(this.form.password) { fd.append('password',this.form.password) }
      if(this.form.password_confirmation) { fd.append('password_confirmation',this.form.password_confirmation) }

      axios({url: '/admin/reset_password/'+ this.$route.params.token ,method: 'post', data: fd})
      .then(res=> {
        if(res.data.statusText == "error"){
          this.serverside_error = res.data.data.errors
        } else {
          this.$store.dispatch("AdminAttempAction", res.data.access_token)
        }
      })
      .catch( error => { /*console.log(error)*/ });
    }
  }
}
</script>
