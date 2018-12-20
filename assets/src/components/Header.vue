<template>
    <header class="header color-2" id="header">
      <b-navbar toggleable="md" type="dark">

        <b-navbar-toggle target="nav_collapse"></b-navbar-toggle>

        <b-navbar-brand to="/">GYM Control</b-navbar-brand>

        <b-collapse is-nav id="nav_collapse">

          <b-navbar-nav>
            <b-nav-item to="/">Inicio</b-nav-item>
            <b-nav-item-dropdown text="Usuarios" right>
              <b-dropdown-item to="Users">Ver Usuarios</b-dropdown-item>
              <b-dropdown-item href="#">Añadir Usuario</b-dropdown-item>
            </b-nav-item-dropdown>
            <b-nav-item-dropdown text="Socios" right>
              <b-dropdown-item href="#">Ver Socios</b-dropdown-item>
              <b-dropdown-item href="#">Añadir Socio</b-dropdown-item>
            </b-nav-item-dropdown>
            <b-nav-item-dropdown text="Estadisticas" right>
              <b-dropdown-item href="#">Asistencias</b-dropdown-item>
            </b-nav-item-dropdown>
            <b-nav-item-dropdown text="Configuracion" right>
              <b-dropdown-item href="#">Planes y Propositos</b-dropdown-item>
              <b-dropdown-item href="#">Permisos</b-dropdown-item>
              <b-dropdown-item href="#">Imagenes</b-dropdown-item>
              <b-dropdown-item href="#">Comunicados</b-dropdown-item>
            </b-nav-item-dropdown>
          </b-navbar-nav>

          <!-- Right aligned nav items -->


        </b-collapse>
      </b-navbar>
      <HeaderAction></HeaderAction>
    </header>
</template>

<script>
import HeaderAction from './HeaderAction.vue';
export default {
    name:'Header',
    components:{
      HeaderAction
    }
}
</script>

<style>

</style>
