<template>
<aside class="main-sidebar sidebar-dark-primary elevation-4" v-if="$store.state.adminAuther != null">
    <!-- Brand Logo -->
    <router-link to="home" class="brand-link">
      <img :src="$store.state.setting.logo_path" :alt="$store.state.setting.name" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{$store.state.setting.name}}</span>
    </router-link>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img :src="$store.state.adminAuther.photo_path" alt="auth.name">
        </div>
        <div class="info">
          <router-link  to="/admin/profile" class="d-block">{{$store.state.adminAuther.name}}</router-link>
        </div>
      </div>


      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <router-link to="/admin/home" class="nav-link">
              <span class="icon"><fa class="nav-icon" icon="home" /></span>
              <p>
                dashboard
              </p>
            </router-link>
          </li>
          <li class="nav-item"  v-if="$store.state.AdminPermission.includes('users-read')" >
            <router-link to="/admin/users" class="nav-link">
              <span class="icon"><fa class="nav-icon" icon="users" /></span>
              <p>
                users
              </p>
            </router-link>
          </li>
          <li class="nav-item" v-if="$store.state.AdminPermission.includes('admin-read')" >
            <router-link to="/admin/admins" class="nav-link">
              <span class="icon"><fa class="nav-icon" icon="users-cog" /></span>
              <p>
                admins
              </p>
            </router-link>
          </li>
          <li class="nav-item"  v-if="$store.state.AdminPermission.includes('pharmacies-read')" >
            <router-link to="/admin/pharmacies" class="nav-link">
              <span class="icon"><fa class="nav-icon" icon="flask" /></span>
              <p>
                pharmacies
              </p>
            </router-link>
          </li>
          <li class="nav-item" v-if="$store.state.AdminPermission.includes('settings-read')" >
            <router-link to="/admin/settings" class="nav-link">
              <span class="icon"><fa class="nav-icon" icon="cogs" /></span>
              <p>
                setings
              </p>
            </router-link>
          </li>    
          <li class="nav-item" v-if="$store.state.AdminPermission.includes('role-read')" >
            <router-link to="/admin/roles" class="nav-link">
               <span class="icon"><fa class="nav-icon" icon="user-cog" /></span>
              <p>
                role
              </p>
            </router-link>
          </li>
          <li class="nav-item"  v-if="$store.state.AdminPermission.includes('timetable-read')">
            <router-link to="/admin/timetables" class="nav-link">
              <span class="icon"><fa class="nav-icon" icon="calendar-alt" /></span>
              <p>
                timetables
              </p>
            </router-link>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
</template>

<script>
export default {
    
}
</script>


<style scoped>
.nav-item{
  height: 40PX;
}
</style>