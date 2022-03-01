<template>
  <div class="card card-primary card-outline">
    <div class="card-body box-profile">

      <div class="row">
          <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile" v-if="form">

                <h3 class="profile-username text-center">{{form.title}}</h3>
                <p class="text-muted text-center">start at : {{form.start_time}}</p>
                <p class="text-muted text-center">end at : {{form.end_time}}</p>
                <p class="text-muted text-center">pharmcay : {{form.pharmacy.name}}</p>
                <p class="text-muted text-center">user : {{form.user.name}}</p>
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
    name: 'showtime',
    data(){
        return {
            form: null,
        }
    },
    methods:{
        read:function(){
            axios({ url: '/timetables/'+ this.$route.params.id, method: 'get' })
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

