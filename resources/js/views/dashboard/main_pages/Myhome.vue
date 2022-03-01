<template>
  <div>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{users_count}}</h3>
                <p>users</p>
              </div>
              <div class="icon">
                <fa icon="users" />
              </div>
              <router-link to="users" class="small-box-footer">More info</router-link>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{pharmacies_count}}</h3>

                <p>pharmacies</p>
              </div>
              <div class="icon">
                <fa class="nav-icon" icon="flask" />
              </div>
              <router-link to="pharmacies" class="small-box-footer">More info</router-link>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{timetables_count}}</h3>
                <p>timetables</p>
              </div>
              <div class="icon">
                <fa icon="calendar-alt" />
              </div>
              <router-link to="timetables" class="small-box-footer">More info</router-link>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{admins_count}}</h3>
                <p>admins</p>
              </div>
              <div class="icon">
                  <fa icon="users-cog" />
              </div>
              <router-link to="admins" class="small-box-footer">More info</router-link>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">

        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
</template>

<script>
export default {
  data(){
    return{
      pharmacies_count:1,
      users_count:1,
      timetables_count:1,
      admins_count:1,
    }
  },
  methods:{
      read:function(page = 1){
            axios({url: '/home', method: 'get',})
            .then(response=> {
              this.pharmacies_count = response.data.data.pharmacies_count
              this.users_count = response.data.data.users_count
              this.timetables_count = response.data.data.timetables_count
              this.admins_count = response.data.data.admins_count
            })
            .catch( error => {/*console.log(error)*/});
        },
  },
  mounted(){
    this.read()
  }
}

</script>

<style>
.quick_access .item{
  padding: 15px;
  height: 80px;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #343a40;
  font-size: 30px;
  border-radius: 8px;
  background-color: #ccc;
  margin-bottom: 30px;
  transition: all .6s ease;
  box-shadow: 1px 1px 5px 2px #0000007a
  
}
.quick_access .item:hover{
  opacity: .8;
}


</style>