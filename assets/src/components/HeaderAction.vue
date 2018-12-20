<template>
  <div class="header-action-bar">
    <b-container>
      <b-row>
        <b-col sm="10" md="10">
          <div class="newsticker">
            <div class="nt-title">
              <strong>Comunicados:</strong>
              <span></span>
            </div>
            <ul>
              <li>
                <a href>Este es un comunicado</a>
              </li>
            </ul>
          </div>
        </b-col>
        <b-col sm="2" md="2">
          <b-btn v-b-modal.loginModal>Login</b-btn>
        </b-col>
      </b-row>
      <!-- Modal Component -->
      <b-modal id="loginModal" title="Iniciar Sesion" hide-footer="false">
        <div>
          <b-form @submit="login">
            <b-form-group id="username-label" label="Nombre de Usuario" label-for="username-input">
              <b-form-input
                id="username-input"
                type="text"
                v-model="form.usuario"
                required
                placeholder="Usuario"
              ></b-form-input>
            </b-form-group>
            <b-form-group id="password-label" label="Contraseña" label-for="password-input">
              <b-form-input
                id="password-input"
                type="password"
                v-model="form.password"
                required
                placeholder="Contraseña"
              ></b-form-input>
            </b-form-group>
            <b-button type="submit" variant="primary">Iniciar Sesion</b-button>
          </b-form>
        </div>
      </b-modal>
    </b-container>
  </div>
</template>

<script>
export default {
  name: 'HeaderAction',
  data() {
    return {
      form: {
        usuario: '',
        password: ''
      }
    }
  },
  methods: {
    login(evt) {
      evt.preventDefault()
      let usuario = this.form.usuario
      let password = this.form.password
      this.$store
        .dispatch('login', { usuario, password })
        .then(() => this.$refs.loginModal.hide())
        .catch(err => console.log(err))
    }
  }
}
</script>

<style>
</style>
