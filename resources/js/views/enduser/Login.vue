<template>
  <div class="enduser">
        <div class="overlay">
            <div class="box">
                <h2 class="box-title">login</h2>
                <p class="box-p">sign in to start your session</p>
                <div class="mb-3">
                    <h2 v-if="serverside_error != null" class="text-danger text-center">{{serverside_error}}</h2>
                    <input  type="email" class="form-control form-control-lg" placeholder="email"
                            v-model="form.email"
                            :class="(v$.form.email.$error) ? 'is-invalid' : '' ">
                    <div class="invalid-feedback" v-if="v$.form.email.$error">{{ v$.form.email.$errors[0].$message }}</div>
                </div>
                <div class="mb-3">
                    <input  type="password" class="form-control form-control-lg" placeholder="password"
                            v-model="form.password"
                            :class="(v$.form.password.$error) ? 'is-invalid' : '' ">
                    <div class="invalid-feedback" v-if="v$.form.password.$error">{{ v$.form.password.$errors[0].$message }}</div>
                </div>
                <div class="mb-3">
                    <button @click="login()" class="btn btn-lg btn-block btn-primary">login</button>
                </div>
                <div class="mb-3">
                    <router-link  to="/register" class="d-block">Register a new membership</router-link>
                    <router-link  to="/forgot_password" class="d-block">I forgot my password</router-link>
                </div>
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

            axios({url: '/auth/login' ,method: 'post', data: fd})
            .then(res=> {
                if(res.data.statusText == "error"){
                    this.serverside_error = res.data.data.error
                } else {
                    this.$store.dispatch("attempAction",res.data.access_token)
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
    .enduser{
        width: 100%;
        height: 100vh;
        background-image: url("../../assets/background.jpg");
        background-size: cover;
        position: relative;
    }
    .enduser .overlay{
        background-color: #000000d6;
        width: 100%;
        height: 100%;
        padding: 15px;
    }
    .enduser .box{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 90%;
        max-width: 500px;
        height: 400px;
    }
    .enduser .box .box-title{
        text-align: center;
        color: #fff;
        text-transform: capitalize;
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 30px;
    }
    .enduser .box .box-p{
        text-align: center;
        color: #fff;
        font-size: 16px;
        margin-bottom: 30px;
    }
    .enduser .order .order-title{
        text-align: center;
        color: #fff;
        font-size: 24px;
        margin-bottom: 20px;
        font-weight: bold;
    }
</style>