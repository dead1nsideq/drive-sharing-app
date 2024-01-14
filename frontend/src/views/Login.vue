<script setup>
import {vMaska} from "maska";
import {reactive, ref} from "vue";
import axios from "axios";

const credentials = reactive({
  phone: null,
  login_code: null,
});

const waitingOnVerification = ref(false);

function handleLogin() {
  axios.post('http://localhost:8000/api/login', {
    phone: credentials.phone.replace(/\D/g, '')
  }).then((response) => {
    console.log(response.data)
  }).catch((error) => {
    console.error(error)
    alert(error.response.data.message)
  })
}

function handleVerification() {
  axios.post('http://localhost:8000/api/login/verify',
      {
        phone: credentials.phone.replace(/\D/g, ''),
        login_code: credentials.login_code
      }).then((response) => {
    console.log(response.data)
  }).catch((error) => {
    console.error(error)
    alert(error.response.data.message)
  })
}
</script>

<template>
  <div class="pt-16">
    <div v-if="!waitingOnVerification">
      <h1 class="text-3xl font-semibold mb-4">Enter your phone number</h1>
      <form action="#" @submit.prevent="handleLogin()" @submit="waitingOnVerification = true">
        <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
          <div class="bg-white px-4 py-5 sm:p-6">
            <div>
              <input v-model="credentials.phone" type="text" v-maska data-maska="+(###) ## ####-###" name="phone"
                     id="phone" placeholder="+1 (234) 56 7789-10"
                     class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none">
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
            <button type="submit"
                    class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">
              Continue
            </button>
          </div>
        </div>
      </form>
    </div>
    <div v-else>
      <h1 class="text-3xl font-semibold mb-4">Enter your verification code</h1>
      <form action="#" @submit.prevent="handleVerification()">
        <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
          <div class="bg-white px-4 py-5 sm:p-6">
            <div>
              <input v-model="credentials.login_code" type="text" v-maska data-maska="######" name="login_code"
                     id="login_code" placeholder="123456"
                     class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none">
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
            <button type="submit"
                    class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">
              Submit
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
