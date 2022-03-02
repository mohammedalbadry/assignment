import "./bootstrap";
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import router from './router/route'
import store from "./store/index"
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import "./assets/icons/fa.js"

window.Vue = require('vue').default;
Vue.config.productionTip = false
Vue.component('fa', FontAwesomeIcon)
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.use(VueToast, { position: 'top-right' })

axios.defaults.baseURL = 'http://127.0.0.1:8000/api';
    
const set_setting = new Promise((resolve) => {
    axios.get(`/settings`).then((res)=>{
            store.dispatch('settingAction', res.data.data)
            document.title = res.data.data.name
  
            var link = document.createElement('link');
                link.rel = 'icon';
                link.id = 'site_icon';
                document.getElementsByTagName('head')[0].appendChild(link);
                link.href = res.data.data.icon_path
                resolve('go to next route')
        })   
});

var token = localStorage.getItem("token")
const valid_authentication = new Promise((resolve) => {
   
  if(token == null){resolve('go to login')}
  else{

    axios({
      url: '/auth/me',
      method: 'post',
      headers: {'authorization': "Bearer " + token}
    })
    .then(res=> {
      
      let current_router = router.history

      store.commit('setError', null)
	    store.commit('setToken', token)
      store.commit('setAuther', res.data)

      if(current_router._startLocation == "/login" || current_router._startLocation  == "/register" ||
         current_router._startLocation  == "/forgot_password" || current_router._startLocation.includes("reset_password"))
      {
        axios.defaults.headers.common['authorization'] = "Bearer " + token
        router.push('/home').catch(() => { /* ignore */ })
      } else {
        axios.defaults.headers.common['authorization'] = "Bearer " + token
        router.push(current_router._startLocation).catch(() => { /* ignore */ })
      }
      resolve('go to next route')

    }).catch( error => {
        store.commit('setError', "Unauthorized")
        store.commit('setToken', null)
        store.commit('setAuther', null)
        localStorage.removeItem("token")         
        resolve('go to next route')

    });

      
      
  }    
});

var admin_token = localStorage.getItem("admin_token")
const valid_admin_authentication = new Promise((resolve) => {
   
  if(admin_token == null){resolve('go to login')}
  else{

    axios({
      url: '/admin/me',
      method: 'post',
      headers: {'authorization': "Bearer " + admin_token}
    })
    .then(res=> {

      let current_router = router.history

      store.commit('setAdminError', null)
      store.commit('setAdminToken', admin_token)

      store.commit('setAdminAuther', res.data.user)
      store.commit('setAdminRole', res.data.roles)
      store.commit('setAdminPermission', res.data.permissions)

      if(current_router._startLocation == "/admin/login" || current_router._startLocation  == "/admin/forgot_password" ||
         current_router._startLocation.includes("/admin/reset_password"))
      {
        axios.defaults.headers.common['authorization'] = "Bearer " + admin_token
        router.push('/admin/home').catch(() => { /* ignore */ })
      } else {
        axios.defaults.headers.common['authorization'] = "Bearer " + admin_token
        router.push(current_router._startLocation).catch(() => { /* ignore */ })
      }
      resolve('go to next route')

    }).catch( error => {
        store.commit('setAdminError', "Unauthorized")
        store.commit('setAdminToken', null)
        store.commit('setAdminAuther', null)
        localStorage.removeItem("admin_token")         
        resolve('go to next route')

    });
      
  }    
});

set_setting.then(()=>{
    valid_authentication.then(()=>{
      valid_admin_authentication.then(()=>{

        const app = new Vue({
            el: '#app',
            router,
            store
        });

      })        
    })
})

  
