<script setup>
function test() {
  axios.get('/user').then(
      (response) => {
      console.log(response)
  }).catch((error) => {
      console.error(error);
  })
}
</script>

<template>
  This is home view,you can see it if you logged in
  <form action="#" @submit.prevent="test">
    <button type="submit">CLICK</button>
  </form>
</template>
