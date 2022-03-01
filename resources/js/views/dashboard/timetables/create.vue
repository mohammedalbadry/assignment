<template>
  <div class="card card-primary card-outline">
    <div class="card-body box-profile">

        <div class="mb-3">
          <div v-if="user" class="btn btn-info btn-block">{{user.name}}</div>
          <div v-if="!user" class="btn btn-danger btn-block"  data-toggle="modal" data-target="#user_modal">You have to choose a user</div>

          <div class="text-danger" v-if="v$.user.$error">{{ v$.user.$errors[0].$message }}</div>
          <div class="text-danger" v-if="serverside_error.user_id">{{ serverside_error.user_id[0] }}</div>
        </div>

        <div class="mb-3">
          <div v-if="pharmacy" class="btn btn-info btn-block">{{pharmacy.name}}</div>
          <div v-if="!pharmacy" class="btn btn-danger btn-block" data-toggle="modal" data-target="#pharmacy_modal">You have to choose a pharmacy</div>

          <div class="text-danger" v-if="v$.pharmacy.$error">{{ v$.pharmacy.$errors[0].$message }}</div>
          <div class="text-danger" v-if="serverside_error.pharmacy_id">{{ serverside_error.pharmacy_id[0] }}</div>
        </div>


        <div class="mb-3">
            <label class="form-label">title</label>
            <input
              v-model="form.title"
              :class="(v$.form.title.$error || serverside_error.title) ? 'is-invalid' : '' "
              type="text" class="form-control" placeholder="title ...">
              <div class="invalid-feedback" v-if="v$.form.title.$error">{{ v$.form.title.$errors[0].$message }}</div>
              <div class="invalid-feedback" v-if="serverside_error.title">{{ serverside_error.title[0] }}</div>
        </div>
        <div class="mb-3">
            <label class="form-label">start time</label>
            <datetime format="YYYY-MM-DD h:i" width="300px" v-model="form.start_time"></datetime>
            <div class="text-danger" v-if="v$.form.start_time.$error">{{ v$.form.start_time.$errors[0].$message }}</div>
            <div class="text-danger" v-if="serverside_error.start_time">{{ serverside_error.start_time[0] }}</div>
        </div>

        <div class="mb-3">
            <label class="form-label">end time</label>
            <datetime format="YYYY-MM-DD h:i" width="300px" v-model="form.end_time"></datetime>
            <div class="text-danger" v-if="v$.form.end_time.$error">{{ v$.form.end_time.$errors[0].$message }}</div>
            <div class="text-danger" v-if="serverside_error.end_time">{{ serverside_error.end_time[0] }}</div>
        </div>

        <button type="button" class="btn btn-primary" @click="create()">save change</button>
    </div>
    

    <div class="all_modals">
        <!-- user Modal -->
        <div class="modal fade" id="user_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">user search</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <div>
                        <div class="mb-3">
                            <input
                              v-model="user_search"
                              @input="user_searchfun()"
                              type="text" class="form-control" placeholder="search ...">
                        </div>
                        <div class="row mb-3" v-if="alluser">
                              <div v-for="(item, index) in alluser.data" :key="index"  @click="getuser(item.id)" class="col-6 item">
                                <h3>{{item.name}}</h3>
                                <p>{{item.email}}</p>
                              </div>
                        </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
              </div>
            </div>
        </div>

        <div class="modal fade" id="pharmacy_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">pharmacy search</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <div>
                        <div class="mb-3">
                            <input
                              v-model="pharmacy_search"
                              @input="pharmacy_searchfun()"
                              type="text" class="form-control" placeholder="search ...">
                        </div>
                        <div class="row mb-3" v-if="allpharmacy">
                              <div v-for="(item, index) in allpharmacy.data" :key="index"  @click="getphamacy(item.id)" class="col-6 item">
                                <h3>{{item.name}}</h3>
                              </div>
                        </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
              </div>
            </div>
        </div>


    </div>


  </div>
</template>

<script>
import { required } from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'
import datetime from 'vuejs-datetimepicker';

export default {
    components: { datetime },
    setup: () => ({ v$: useVuelidate() }),
    validations () {
        return {
            form: {
                title: { required },
                start_time: { required },
                end_time: { required },
            },
            user: { required },
            pharmacy: { required },
        }
    },
    data(){
        return {
            form: {
              title: null,
              start_time: new Date().toISOString().replace(/T/, ' ').replace(/\..+/, ''),
              end_time: new Date().toISOString().replace(/T/, ' ').replace(/\..+/, '') 
            },
            serverside_error:{},
            alluser:null,
            user:null,
            user_search:null,

            allpharmacy:null,
            pharmacy:null,
            pharmacy_search:null,
        }
    },
    methods:{
        getuser(id){
            axios({ url: '/users/'+ id, method: 'get' })
            .then(response=> {
                this.user = response.data.data   
                document.querySelectorAll('[data-dismiss="modal"]').forEach(element => element.click())              
            })
            .catch( error => {/*console.log(error)*/});
        },
        user_searchfun(page = 1){
            const search = this.user_search != null ?  this.user_search : null
            axios({
                    url: '/users?page=' + page,
                    method: 'get',
                    params: { 
                        search
                    }
            })
            .then(response=> {
              this.alluser = response.data.data
            })
            .catch( error => {/*console.log(error)*/});
        },
        getphamacy(id){
            axios({ url: '/pharmacies/'+ id, method: 'get' })
            .then(response=> {
                this.pharmacy = response.data.data   
                document.querySelectorAll('[data-dismiss="modal"]').forEach(element => element.click())              
            })
            .catch( error => {/*console.log(error)*/});
        },
        pharmacy_searchfun(page = 1){
            const search = this.pharmacy_search != null ?  this.pharmacy_search : null
            axios({
                    url: '/pharmacies?page=' + page,
                    method: 'get',
                    params: { 
                        search
                    }
            })
            .then(response=> {
              this.allpharmacy = response.data.data
            })
            .catch( error => {/*console.log(error)*/});
        },
        async create (){
            //front end validation
            var result1 = await this.v$.form.$validate(),
                result2 = await this.v$.user.$validate(),
                result3 = await this.v$.pharmacy.$validate()
            if (!result1 || !result2 || !result3) { return }

            //form data
            var fd = new FormData()
            if(this.form.title) { fd.append('title',this.form.title) }
            if(this.form.start_time) { fd.append('start_time',this.form.start_time) }
            if(this.form.end_time) { fd.append('end_time',this.form.end_time) }

            if(this.user) { fd.append('user_id',this.user.id) }
            if(this.pharmacy) { fd.append('pharmacy_id',this.pharmacy.id) }

            axios({url: 'timetables', method: 'post', data: fd})
            .then(response=> {
                              console.log(response)

              if(response.data.statusText == "created"){
                this.$router.push({
                  name: 'timetables', 
                  params: { message: response.data.message }})
              }
              if(response.data.statusText == "error"){
                this.serverside_error = response.data.data.errors
              }
            }).catch( error => {/*console.log(error)*/});

        },
    },
    mounted() {
      if(this.$route.params.user){
        this.getuser(this.$route.params.user)
      }
      if(this.$route.params.pharmacy){
        this.getphamacy(this.$route.params.pharmacy)
      }
    }
}
</script>

<style>
.item{
  cursor: pointer;
  text-align: center;
  border: 1px solid #ccc;
}
</style>