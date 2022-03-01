<template>
  <div class="card card-primary card-outline">
    <div class="card-body box-profile">

      <div class="row">
          <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <h3 class="profile-username text-center">{{form.name}}</h3>
              </div>
              <!-- /.card-body -->
            </div>
          </div>

          <div class="col-md-9">

              <div class="mb-3">
                  <div class="row">
                    <div class="col-6 col-md-4" v-for="(item, index) in form.permissions" :key="index">
                        <div class="role-btn m-2"> {{item.display_name}} 
                              <input class="form-check-input" type="checkbox" checked="checked">
                              <div class="checked">
                                <span class="icon"><fa icon="check-circle"/></span>
                              </div>
                        </div>
                    </div>
                  </div>
              </div>

          </div>

      </div>


    </div>
  </div>
</template>

<script>

export default {
    name: 'show_role',
    data(){
        return {
            form: {},
        }
    },
    methods:{
        read:function(){
            axios({ url: '/roles/'+ this.$route.params.id, method: 'get' })
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


<style scoped>
.role-btn{
  position: relative;
  padding: 8px 12px;
  border: none;
  font-size: 1.2em;
  background-color: #343a40;
  cursor: pointer;
  color: #ffffff;
}
.role-btn input{
  display: none;
  z-index: -100;
  width: 100%;
  height: 100%;
  position: absolute;
  top: -4px;
  left: 20px;
  cursor: pointer;
  opacity: 0;
}
.role-btn .checked{
  z-index: 50;
  position: absolute;
  box-sizing: border-box;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  box-shadow: 2px 2px 4px 2px #d63031;
  border-radius: 5px;
  transition: all .2s ease;
}
.role-btn .checked .icon{
    z-index: 50;
    position: absolute;
    box-sizing: border-box;
    top: 8px;
    right: 10px;
    display: block;
    color: #00b894;
    transition: all .2s ease;
    opacity: 0;
}
.role-btn input:checked + .checked{
    box-shadow: 2px 2px 4px 2px #00b894;
}
.role-btn input:checked + .checked .icon{
    opacity: 1;
}
</style>