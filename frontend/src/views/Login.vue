<script setup>
import {vMaska} from "maska";
import {reactive} from "vue";
import {useAuthStore} from "@/stores/auth.js";
import router from "@/router/index.js";
import axios from "axios";

const credentials = reactive({
  phone: '',
  login_code: null,
});

const authStore = useAuthStore();

useAuthStore();
function login() {
  authStore.handleLogin({ phone: credentials.phone.replace(/\D/g, '') })
}

function verification() {
  // authStore.handleVerification({
  //   phone: credentials.phone.replace(/\D/g, ''),
  //   login_code: credentials.login_code
  // })
  // credentials.login_code = null;
  // credentials.phone = '';
  // authStore.state.waitingOnVerification = false;
  axios.post('/login/verify',{
    phone: credentials.phone.replace(/\D/g, ''),
    login_code: credentials.login_code
  }).then((response) => {
    localStorage.setItem('token',response.data.token);
    authStore.state.logged = true;
    authStore.state.user_id = response.data.user_id;
    credentials.login_code = null;
    credentials.phone = '';
    authStore.state.waitingOnVerification = false;
    router.push({
      name: 'home'
    })
  }).catch((error) => {
    console.error(error)
    alert(error.response.data.message)
  })
}

</script>

<template>
  <div class="pt-16">
    <div v-if="!authStore.state.waitingOnVerification">
      <h1 class="text-3xl font-semibold mb-4">Enter your phone number</h1>
      <form action="#" @submit.prevent="login()">
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
      <form action="#" @submit.prevent="verification()">
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
