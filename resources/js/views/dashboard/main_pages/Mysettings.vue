<template>
    <div class="card card-outline card-primary">
        <!-- form start -->
        <div class="card-body">

            <div class="mb-3">
                <label class="form-label">name</label>
                <input
                    v-model="settings.name"
                    :class="(v$.settings.name.$error || serverside_error.name) ? 'is-invalid' : '' "
                    type="text" class="form-control" placeholder="name ...">
                <div class="invalid-feedback" v-if="v$.settings.name.$error">{{ v$.settings.name.$errors[0].$message }}</div>
                <div class="invalid-feedback" v-if="serverside_error.name">{{ serverside_error.name[0] }}</div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">description</label>
                <textarea
                    v-model="settings.description"
                    :class="(v$.settings.description.$error || serverside_error.description) ? 'is-invalid' : '' "
                    class="form-control" placeholder="description ..."></textarea>
                <div class="invalid-feedback" v-if="v$.settings.description.$error">{{ v$.settings.description.$errors[0].$message }}</div>
                <div class="invalid-feedback" v-if="serverside_error.description">{{ serverside_error.description[0] }}</div>
            </div>

            <div class="mb-3">
                <div class="row">
                <div class="col-9 col-sm-10">
                    <div class="form-group">
                        <label for="exampleInputFile">logo</label>
                        <div 
                            :class="(v$.settings.logo.$error || serverside_error.logo) ? 'is-invalid' : '' "
                            class="input-group">
                            <div class="custom-file">
                                <input 
                                    @change="processFile($event, 'logo')"
                                    :class="(v$.settings.logo.$error || serverside_error.logo) ? 'is-invalid' : '' "
                                    type="file" class="custom-file-input" id="exampleInputFile">
                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                            </div>
                        </div>
                        <div class="invalid-feedback" v-if="v$.settings.logo.$error">{{ v$.settings.logo.$errors[0].$message }}</div>
                        <div class="invalid-feedback" v-if="serverside_error.logo">{{ serverside_error.logo[0] }}</div>
                    </div>
                </div>
                <div class="col-3 col-sm-2">
                    <img class="img_preview" :src="settings.logo_path" alt="">
                </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="row">
                <div class="col-9 col-sm-10">
                    <div class="form-group">
                        <label for="exampleInputFile">icon</label>
                        <div 
                            :class="(v$.settings.icon.$error || serverside_error.icon) ? 'is-invalid' : '' "
                            class="input-group">
                            <div class="custom-file">
                                <input 
                                    @change="processFile($event, 'icon')"
                                    :class="(v$.settings.icon.$error || serverside_error.icon) ? 'is-invalid' : '' "
                                    type="file" class="custom-file-input" id="exampleInputFile">
                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                            </div>
                        </div>
                        <div class="invalid-feedback" v-if="v$.settings.icon.$error">{{ v$.settings.icon.$errors[0].$message }}</div>
                        <div class="invalid-feedback" v-if="serverside_error.icon">{{ serverside_error.icon[0] }}</div>
                    </div>
                </div>
                <div class="col-3 col-sm-2">
                    <img class="img_preview" :src="settings.icon_path" alt="">
                </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">status</label>
                <select 
                    v-model="settings.status"
                    :class="(v$.settings.status.$error || serverside_error.status) ? 'is-invalid' : '' "
                    class="custom-select form-select form-select-lg" aria-label=".form-select-lg example">
                    <option value="open">open</option>
                    <option value="close">close</option>
                </select>
                <div class="invalid-feedback" v-if="v$.settings.status.$error">{{ v$.settings.status.$errors[0].$message }}</div>
                <div class="invalid-feedback" v-if="serverside_error.status">{{ serverside_error.status[0] }}</div>
            </div>

            <div class="mb-3">
                <label class="form-label">alt text</label>
                <textarea
                    v-model="settings.alt_text"
                    :class="(v$.settings.alt_text.$error || serverside_error.alt_text) ? 'is-invalid' : '' "
                    class="form-control" placeholder="alt_text ..."></textarea>
                <div class="invalid-feedback" v-if="v$.settings.alt_text.$error">{{ v$.settings.alt_text.$errors[0].$message }}</div>
                <div class="invalid-feedback" v-if="serverside_error.alt_text">{{ serverside_error.alt_text[0] }}</div>
            </div>

            <button type="button" class="btn btn-primary" @click="update()">save change</button>

        </div>
        <!-- /.card-body -->


    </div>
</template>

<script>
import { minLength, required} from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'

export default {
    name: 'settings',
    setup: () => ({ v$: useVuelidate() }),
    validations () {
        return {
            settings: {
                name: { minLength: minLength(2), required },
                logo: { required },
                icon: { required },
                description: { minLength: minLength(2), required },
                status: { required },
                alt_text: { minLength: minLength(2), required },
            }
        }
    },
    data(){
        return {
            settings: {},
            serverside_error: {},
        }
    },
    methods:{
        processFile(event, type = "add") {
          var file = event.target.files[0] 
          if(type == "logo"){
           this.settings.logo = file
           this.settings.logo_path = URL.createObjectURL(file);
          }else{
            this.settings.icon = file
            this.settings.icon_path = URL.createObjectURL(file);
          }
        },
        read:function(){
            axios({ url: '/settings', method: 'get' })
            .then(response=> {
                this.settings = response.data.data
            })
            .catch( error => {/*console.log(error)*/});
        },
        async update (){

            //front end validation
            const result = await this.v$.settings.$validate()
            if (!result) { return}

            var fd = new FormData()
            if(this.settings.name) { fd.append('name',this.settings.name) }
            if(this.settings.description) { fd.append('description',this.settings.description) }
            if(this.settings.alt_text) { fd.append('alt_text',this.settings.alt_text) }
            if(this.settings.status) { fd.append('status',this.settings.status) }
            if(typeof(this.settings.logo) == "object"){
              fd.append('logo', this.settings.logo)
            }
            if(typeof(this.settings.icon) == "object"){
              fd.append('icon', this.settings.icon)
            }

            axios({url: '/settings', method: 'post', data: fd})
            .then(response=> {
                if(response.data.statusText == "error"){
                    this.serverside_error = response.data.data.errors
                } else if(response.data.statusText == "updated") {
                    this.read()
                    Vue.$toast.open({
                        message: response.data.message,
                        type: 'info',
                    });
                    this.serverside_error = {}

                    this.$store.commit('setSetting', this.settings)
                    document.title = this.settings.name
                    var link = document.getElementById('site_icon')
                    link.href = this.settings.icon_path
                }
               
            })
            .catch( error => {/*console.log(error)*/});

        },
    },
    mounted() {
        this.read();
    }
}
</script>

<style>
.img{
  width: 80px;
  overflow: hidden;
  border: 1px solid #007bff;
  padding: 2px;
  margin: 0 auto 10px;
}
.img img{
  width: 100%;
}
.img_preview{
  max-height: 150px;
  max-width: 100%;
}
</style>