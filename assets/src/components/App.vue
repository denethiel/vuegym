<template>
  <div class="wrapper color-option-2">
    <Header></Header>
    <transition name="fade" mode="out-in">
      <router-view></router-view>
    </transition>
    
  </div>
</template>

<script>
import Header from './Header.vue'
export default {
  name: 'App',
  components:{
    Header
  }
}
</script>

<style scoped>
</style>
