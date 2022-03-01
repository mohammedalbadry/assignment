import Vue from 'vue'
import Vuex from 'vuex'
import router from '../router/route'

Vue.use(Vuex)

const store = new Vuex.Store({
	state: {
		setting: null,
        auther: null,
		token: null,
		authError: null,

		adminAuther:null,
		adminRole:null,
		AdminPermission:null,
		adminToken:null,
		adminAuthError:null,
	},
	mutations: {
		setAuther(state, payload){
			state.auther = payload
			localStorage.setItem("auther", payload)
		},
		setToken(state, payload){
			state.token = payload
			localStorage.setItem("token", payload)
		},
		setError(state, payload){ state.authError = payload },
		setSetting(state, payload){ state.setting = payload },

		setAdminAuther(state, payload){
			state.adminAuther = payload
			localStorage.setItem("adminAuther", payload)
		},
		setAdminRole(state, payload){
			if(payload == null){
				state.adminRole = payload
			}else{
				var roles = []
				payload.forEach(element => {
					roles.push(element.name);
				});
				state.adminRole = roles
			}
		},
		setAdminPermission(state, payload){
			if(payload == null){
				state.adminRole = payload
			}else{
				var permissions = []
				payload.forEach(element => {
					permissions.push(element.name);
				});
				state.AdminPermission = permissions
			}
		},
		setAdminToken(state, payload){
			state.adminToken = payload
			localStorage.setItem("admin_token", payload)
		},
		setAdminError(state, payload){
			state.adminAuthError = payload
		},
	},
	actions: {
		loginAction (context, payload) {
            var fd = new FormData()
            fd.append('email', payload.email)
			fd.append('password', payload.password)
			
            axios({ url: '/auth/login/', method: 'post',  data: fd })
            .then(response=> {
                if(response.data.status == "error"){
					context.commit('setError', response.data.error)
                }
                if(response.data.status == "success"){
					context.dispatch("attempAction",response.data.access_token)
                }
            })
            .catch( error => { /*console.log(error)*/ });
		},
		attempAction(context, token){
			axios({
				url: 'auth/me/',
				method: 'post',
				headers: {'authorization': "Bearer " + token}
			})
			.then(res=> {
				context.commit('setError', null)
				context.commit('setToken', token)
				context.commit('setAuther', res.data)
				var current_path = router.history.current.path
				console.log(current_path)
				if(current_path == "/login" || current_path == "/register" ||
				   current_path == "/forgot_password" || current_path.includes("reset_password") ){
					axios.defaults.headers.common['authorization'] = "Bearer " + token
					router.push('/home').catch(() => { /* ignore */ })
				} else {
					router.push(router.history.current.path).catch(() => { /* ignore */ })
				}
				
			})
			.catch( error => {
				context.commit('setError', "Unauthorized")
				context.commit('setToken', null)
				context.commit('setAuther', null)
				localStorage.removeItem("token")
			});

				
		
		},
		logoutAction(context) {
			axios({
				url: '/auth/logout/',
				method: 'post',
			})
			.then(response=> {
				context.commit('setError', null)
				context.commit('setToken', null)
				context.commit('setAuther', null)
				localStorage.removeItem("token")
				localStorage.removeItem("auther")
				router.push('/login').catch(() => { /* ignore */ })
			})
			.catch( error => { /*console.log(error)*/ });
		},
		settingAction(context, settings){
			context.commit('setSetting', settings)
		},

		AdminloginAction (context, payload) {
            var fd = new FormData()
            fd.append('email', payload.email)
            fd.append('password', payload.password)
            axios({
                    url: '/auth/login/',
                    method: 'post',
                    data: fd,
            })
            .then(response=> {
                if(response.data.status == "error"){
					context.commit('setAdminError', response.data.error)
                }
                if(response.data.status == "success"){
					context.dispatch("AdminAttempAction",response.data.access_token)
                }
            })
            .catch( error => { /*console.log(error)*/ });
		},
		AdminAttempAction(context, token){

			axios({
				url: 'admin/me/',
				method: 'post',
				headers: {'authorization': "Bearer " + token}
			})
			.then(res=> {
				context.commit('setAdminError', null)
				context.commit('setAdminToken', token)

				context.commit('setAdminAuther', res.data.user)
				context.commit('setAdminRole', res.data.roles)
				context.commit('setAdminPermission', res.data.permissions)
				
				var current_path = router.history.current.path
				if(current_path == "/admin/login" ||  current_path == "/admin/forgot_password" ||
				   current_path.includes("admin/reset_password") ){
					axios.defaults.headers.common['authorization'] = "Bearer " + token
					router.push('/admin/home').catch(() => { /* ignore */ })
				} else {
					router.push(router.history.current.path).catch(() => { /* ignore */ })
				}			
			})
			.catch( error => {
				context.commit('setAdminError', "Unauthorized")
				context.commit('setAdminToken', null)
				context.commit('setAdminAuther', null)
				localStorage.removeItem("token")
			});

				
		
		},
		AdminLogoutAction(context) {
			axios({	url: '/admin/logout/', method: 'post' })
			.then(response=> {
				context.commit('setAdminError', null)
				context.commit('setAdminToken', null)
				context.commit('setAdminAuther', null)
				context.commit('setAdminRole', null)
				context.commit('setAdminPermission', null)
				localStorage.removeItem("admin_token")
				localStorage.removeItem("adminAuther")
				router.push('/admin/login').catch(() => { /* ignore */ })
			})
			.catch( error => { /*console.log(error)*/ });
		},
	}

})

export default store
