<template>
  <div class="card card-primary card-outline">
    <div class="card-body box-profile">

      <div class="row">
          <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                       :src="form.photo_path"
                       :alt="form.name">
                </div>

                <h3 class="profile-username text-center">{{form.name}}</h3>
                <h3 class="profile-username text-center">{{form.roles[0].name}}</h3>

                <p class="text-muted text-center">{{form.email}}</p>
              </div>
              <!-- /.card-body -->
            </div>
          </div>

          <div class="col-md-9">
                <h4>There is no custom data at the moment</h4>
          </div>
      </div>


    </div>
  </div>
</template>

<script>

export default {
    name: 'showadmin',
    data(){
        return {
            form: {},
        }
    },
    methods:{
        read:function(){
            axios({ url: '/admins/'+ this.$route.params.id, method: 'get' })
            .then(response=> {
                this.form = response.data.data                 
            })
            .catch( error => {/*console.log(error)*/});
        },
    },
    mounted(){
      this.read()
    }
}
</script>

