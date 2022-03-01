<template>
<div class="wrapper">
  <mynavbar></mynavbar>
  <mysidebar></mysidebar>

  <div class="content-wrapper">

    <mybreadcrumb></mybreadcrumb>

    <section class="content">
      <div class="container-fluid">

          <router-view></router-view>
          
      </div>
    </section>
  </div>

  <myfooter></myfooter>

</div>
</template>

<script>
import '../assets/adminlte/css/adminlte.min.css' 
import '../assets/adminlte/js/adminlte.min.js' 


import Mynavbar from '../components/__Mynavbar'
import Mysidebar from '../components/__Mysidebar' 
import Mybreadcrumb from '../components/__Mybreadcrumb' 
import Myfooter from '../components/__Myfooter' 


export default {
    name:'app',
    components:{
        Mynavbar,
        Mysidebar,
        Mybreadcrumb,
        Myfooter
    }

}
</script>

<style>

</style>