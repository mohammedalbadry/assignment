<template>
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="row">
                <div class="col-md-5">
                     <form action="" method="get">
                        <div class="input-group mb-2">
                            <input @input.prevent="searchfun()" name="search" v-model="searchItems" type="text" class="form-control" placeholder="search ...">
                            <span class="input-group-append">
                              <button @click.prevent="searchfun()" type="submit" class="btn btn-primary btn-flat">search</button>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="col-md-7">
                    <router-link to="pharmacies/create" class="btn btn-primary">
                        add
                    </router-link>
                </div>
            </div>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <div class="card-body">
            <table class="table table-bordered">
                <thead>                  
                <tr>
                    <th>name</th>
                </tr>
                </thead>
                <tbody>
                     <tr v-for="(item, index) in allItem.data" :key="index">
                        <td>{{item.name}}</td>
                        <td>
                            <router-link v-if="$store.state.AdminPermission.includes('pharmacies-update')" :to="'pharmacies/edit/' + item.id" class="btn btn-primary btn-sm">
                                edit
                            </router-link>
                            <router-link v-if="$store.state.AdminPermission.includes('pharmacies-read')" :to="'pharmacies/show/' + item.id" class="btn btn-success btn-sm">
                                show
                            </router-link>
                            <router-link v-if="$store.state.AdminPermission.includes('timetable-create')" :to="'timetables/create/pharmacy/' + item.id" class="btn btn-info btn-sm">
                                new time
                            </router-link>
                            <button v-if="$store.state.AdminPermission.includes('pharmacies-delete')" @click="passActionItem(item)" type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete_modal">
                                delete <i class="fa fa-trash-alt"></i>
                            </button>
                        </td>
                     </tr>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->

        <div class="all_modals">
            <!-- delete Modal -->
            <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">delete pharmacy</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Confirm deletion of <strong>{{actionItem.name}}</strong></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button @click="destroy()" type="button" class="btn btn-danger">Confirm</button>
                    </div>
                </div>
            </div>
            </div>
        </div>

    </div>
</template>


<script>
export default {
    data(){
        return {
            allItem: {},
            actionItem:{},
            searchItems: null
        }
    },
    methods:{
        passActionItem:function(item){
            this.actionItem = item
        },
        read:function(page = 1){
            const search = this.searchItems != null ?  this.searchItems : null
            axios({
                    url: '/pharmacies?page=' + page,
                    method: 'get',
                    params: { 
                        search
                    }
            })
            .then(response=> {
              this.allItem = response.data.data
            })
            .catch( error => {/*console.log(error)*/});
        },
        destroy:function(){

            var fd = new FormData()
            fd.append("_method", "delete");

            axios({ url: '/pharmacies/' + this.actionItem.id, method: 'post', data: fd})
            .then(response=> {
                if(response.data.statusText == "error"){
                    this.editDataErrors = response.data.errors
                } else if(response.data.statusText == "deleted") {
                    this.read()
                    Vue.$toast.open({
                        message: response.data.message,
                        type: 'info',
                    });       
                    document.querySelectorAll('[data-dismiss="modal"]').forEach(element => element.click())
                }
            })
            .catch( error => {
               //console.log(error)
            });


        },
        searchfun:function(){
            this.read()
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
