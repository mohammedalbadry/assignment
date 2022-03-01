<template>
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><fa icon="bars" /></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">


           <button class="btn btn-light btn-sm mx-2" @click="logout()">
               <span class="icon"><fa class="nav-icon" icon="sign-out-alt" /></span>
            </button>

        </ul>
    </nav>
</template>

<script>
export default {
    methods:{
        logout(){
            this.$store.dispatch('AdminLogoutAction')
        }  
    }
}
</script>

<style>

</style>