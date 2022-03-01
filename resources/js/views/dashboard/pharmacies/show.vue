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
                <FullCalendar :options="calendarOptions" />
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
    name: 'showuser',
    components: {
        FullCalendar
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
            form: {},
        }
    },
    methods:{
        read:function(){
            axios({ url: '/pharmacies/'+ this.$route.params.id, method: 'get' })
            .then(response=> {
                this.form = response.data.data
                response.data.data.timetabls.forEach(element => {
                  var item = {
                            title: element.title,
                            start: element.start_time,
                            end: element.end_time,
                        }
                  this.calendarOptions.events.push(item);
                });
                 
            })
            .catch( error => {/*console.log(error)*/});
        },
    },
    mounted(){
      this.read()
    }
}
</script>

