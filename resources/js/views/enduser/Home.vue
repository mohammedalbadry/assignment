<template>
  <div>
       <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
           <div class="container">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <div class="auth_user mr-auto d-flex">

                        <div class="dropdown">
                            <button class="btn btn-secondary mx-1" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{this.$store.state.auther.name}}
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <router-link class="dropdown-item" to="/settings">settngs</router-link>
                                <div class="dropdown-item btn" @click="logout()">logout</div>
                            </div>
                        </div>

                        <div class="dropdown">
                            <button class="btn btn-secondary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <fa icon="bell" />
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>

                        

                    </div>
                </div>
                <router-link class="navbar-brand" to="/">{{this.$store.state.setting.name}}</router-link>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
           </div>
           
      </nav>
      <div class="enduser-main">
        <div class="container">

          <div class="row my-3">
              <div class="col-md-6">
                    <form action="" method="get">
                        <div class="input-group mb-2">
                            <input @input.prevent="searchfun()" v-model="searchItems" name="search" value="" type="text" class="form-control" placeholder="search ...">
                            <span class="input-group-append">
                              <button @click.prevent="searchfun()" type="submit" class="btn btn-dark btn-flat">search</button>
                            </span>
                        </div>
                    </form>
              </div>
              <div class="col-md-6">
                  <button type="button" class="btn btn-dark btn-block" data-toggle="modal" data-target="#exampleModal">
                    filter
                  </button>
              </div>
          </div>
          <FullCalendar :options="calendarOptions" />

        </div>
      </div>
      <footer class="enduser-footer bg-dark">
          <div class="container">
              text
          </div>
      </footer>

      <div class="all_modall">

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-danger text-center">Click OK after select time</p>
                <div class="mb-3">
                    <label class="form-label">start time</label>
                    <datetime format="YYYY-MM-DD" width="300px" v-model="filter_start"></datetime>
                </div>

                <div class="mb-3">
                    <label class="form-label">end time</label>
                    <datetime format="YYYY-MM-DD" width="300px" v-model="filter_end"></datetime>
                </div>
            </div>
            <div class="modal-footer">
                <button v-if="filter_start != null || filter_end != null" @click="clearfilter()" type="button" class="btn btn-info mr-auto">clear filter</button>

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button @click="filterfun()" type="button" class="btn btn-primary">filter now</button>
            </div>
            </div>
        </div>
        </div>

      </div>


  </div>
</template>

<script>
import '@fullcalendar/core/vdom' // solves problem with Vite
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import datetime from 'vuejs-datetimepicker';

export default {
    name: "home",
    components: {
        datetime,
        FullCalendar // make the <FullCalendar> tag available
    },
    data(){
        return {
            calendarOptions: {
                plugins: [ dayGridPlugin, interactionPlugin ],
                initialView: 'dayGridMonth',
                navLinks: true,
                headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,dayGridWeek,dayGridDay'
                },
                events: []
            },
            allItem: {},
            actionItem:{},
            filter_start: null,
            filter_end: null,
            searchItems: null
        }
    },
    methods:{
        passActionItem:function(item){
            this.actionItem = item
        },
        read:function(){
            const search = this.searchItems != null ?  this.searchItems : null
            const filter_start = this.filter_start != null ?  this.filter_start : null
            const filter_end = this.filter_end != null ?  this.filter_end : null
            axios({
                    url: '/gettimetables',
                    method: 'get',
                    params: { 
                        search,
                        filter_start,
                        filter_end
                    }
            })
            .then(response=> {
              this.allItem = response.data.data
              this.calendarOptions.events = [];
                response.data.data.forEach(element => {
                  var item = {
                            title: element.pharmacy['name'],
                            start: element.start_time,
                            end: element.end_time,
                        }
                  this.calendarOptions.events.push(item);
                });
            })
            .catch( error => {/*console.log(error)*/});
        },
        destroy:function(){
            var fd = new FormData()
            fd.append("_method", "delete");
            axios({
                    url: '/Employee/' + this.actionItem.id,
                    method: 'post',
                    baseURL: 'http://127.0.0.1:8000/api/',
                    data: fd,
            })
            .then(response=> {
                if(response.data.status == "error"){
                    this.editDataErrors = response.data.errors
                } else if(response.data.status == "success") {
                    this.read()
                    Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    })
                    this.editDataErrors = {}
                    document.querySelectorAll('[data-dismiss="modal"]').forEach(element => element.click())
                }
               
            })
            .catch( error => {/*console.log(error)*/});
        },
        searchfun:function(){
            this.read()
        },
        filterfun:function(){
            this.read()
            document.querySelectorAll('[data-dismiss="modal"]').forEach(element => element.click())
        },
        clearfilter(){
            this.filter_start = null
            this.filter_end = null
        },
        logout(){
            this.$store.dispatch('logoutAction')
        }  
    },
    mounted() {
        this.read();
        if(this.$route.params.message){
            Vue.$toast.open({
                message: this.$route.params.message,
                type: 'info',
            });
        }
    }
}
</script>


<style>
.enduser-nav,
.enduser-footer{
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 60px;
    padding-left: 15px;
    padding-right: 15px;
}
.enduser-main{
    min-height: calc(100vh - 120px);
}
.enduser-tabletitle{
    margin: 50px 0;
    text-align: center;
    color: #0984e3;
}
.timebox{
    
}
</style>