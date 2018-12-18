<template>
    <header class="header color-2" id="header">
      <div class="logo-nav">
        <b-container fluid>
          <b-row>
            <b-col md="3" class="nop">
              <div class="logo">
                <b-link to="Home">
                  <img src="assets/images/logo.png" alt="">
                </b-link>
                <!-- Login Button -->
              </div>
            </b-col>
            <b-col md="9">
                
              <b-navbar toggleable="md" class="navbar-nav">
                  <b-navbar-toggle target="nav_collapse"></b-navbar-toggle>
                  <b-collapse is-nav id="nav_collapse">
                    <b-navbar-nav>
                        <b-nav-item href="#">Inicio</b-nav-item>
                        <b-nav-item-dropdown text="Usuarios" right>
                            <b-dropdown-item href="#">Ver Usuarios</b-dropdown-item>
                            <b-dropdown-item href="#">Añadir Usuario</b-dropdown-item>
                        </b-nav-item-dropdown>
                    </b-navbar-nav>
                    </b-collapse>
              </b-navbar>
            </b-col>
          </b-row>
        </b-container>
      </div>
    </header>
</template>

<script>
export default {
    name:'Header'
}
</script>

<style>

</style>
