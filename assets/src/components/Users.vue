<template>
  <section class="section-wrapper">
    <b-container>
      <b-row>
        <b-col md="2">
          <h2 class="section-title">Usuarios</h2>
        </b-col>
        <b-col md="6">
          <b-link href class="default-btn">Agregar Nuevo Usuario</b-link>
        </b-col>
        <b-col md="4">
          <b-form-input size class="mr-sm-2" type="text" placeholder="Buscar"/>
        </b-col>
        <b-col md="12"></b-col>
      </b-row>
    </b-container>
  </section>
</template>

<script>
import axios from 'axios'
export default {
	name: 'Users',
	mounted: function() {
		console.log('Mounted Baby')
		axios
			.get('http://localhost:8888/ci-rest/api/main/test')
			.then(resp => {
				console.log(resp)
			})
			.catch(err => {
				console.log(err)
			})
	}
}
</script>

<style scoped>
</style>
