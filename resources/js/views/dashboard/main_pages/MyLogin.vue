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
                <div class="row">
                    <!-- /.col -->
                    <div class="col-12">
                        <button @click.prevent="login()" class="btn btn-primary btn-block">Sign In</button>
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

import { minLength, email, required,} from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'

export default {
    name: 'login',
    setup: () => ({ v$: useVuelidate() }),
    validations () {
        return {
            form: {
                email: { required, email },
                password: { minLength: minLength(6), required },
            }
        }
    },
    data(){
        return {
            form:{
                email: null,
                password: null,
            },
            serverside_error: null
        }
    },
    methods:{
        async login(){
            //front end validation
            const result = await this.v$.form.$validate()
            if (!result) { return}

            //request from data
            var fd = new FormData()
            if(this.form.email) { fd.append('email',this.form.email) }
            if(this.form.password) { fd.append('password',this.form.password) }

            axios({url: '/admin/login' ,method: 'post', data: fd})
            .then(res=> {
                if(res.data.statusText == "error"){
                    this.serverside_error = res.data.data.error
                } else {
                    this.$store.dispatch("AdminAttempAction",res.data.access_token)
                }

            })
            .catch( error => { /*console.log(error)*/ });
        }
    },
    created(){

    }
}
</script>

<style>

</style>