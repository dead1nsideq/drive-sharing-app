<script setup>

import {onMounted, reactive, ref} from "vue";
import router from "@/router/index.js";
import Error from "@/components/Error.vue";

const driver = reactive({
    year: null,
    make: '',
    model: '',
    color: '',
    licence_plate: '',
})

const errors = reactive({
  year: null,
  make: '',
  model: '',
  color: '',
  licence_plate: '',
})

onMounted(() => {
    getInfoAboutDriver()
})
const getInfoAboutDriver = () => {
    axios.get('/driver').then((response) => {
      if (response.driver) {
        driver.year = response.data.driver.year
        driver.make = response.data.driver.make
        driver.model = response.data.driver.model
        driver.color = response.data.driver.color
        driver.licence_plate = response.data.driver.licence_plate
        }
    })
}

const handleSaveDriver = () => {
    axios.post('/driver',driver).then(
        (response) => {
          router.push({
            name: 'standby'
          })
        }
    ).catch(
        (error) => {
          console.log('driver catch error')
          errors.year = error.response.data.errors.year[0]
          errors.make = error.response.data.errors.make[0]
          errors.model = error.response.data.errors.model[0]
          errors.color = error.response.data.errors.color[0]
          errors.licence_plate = error.response.data.errors.licence_plate[0]
        }
    )
}
</script>

<template>
  <div class="pt-16">
    <h1 class="text-3xl font-semibold mb-4">Driver and car details</h1>
    <form action="#" @submit.prevent="handleSaveDriver">
      <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
        <div class="bg-white px-4 py-5 sm:p-6">
          <div class="mt-2">
            <Error class="mt-2" :message="errors.year"></Error>
            <input type="number" name="year" id="year" v-model="driver.year" placeholder="Car Year"
                   class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none">
          </div>
          <div class="mt-2">
            <Error class="mt-2" :message="errors.make"></Error>
            <input type="text" name="make" id="make" v-model="driver.make" placeholder="Make"
                   class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none">
          </div>
          <div class="mt-2">
            <Error class="mt-2" :message="errors.model"></Error>
            <input type="text" name="model" id="model" v-model="driver.model" placeholder="Model"
                   class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none">
          </div>
          <div class="mt-2">
            <Error class="mt-2" :message="errors.color"></Error>
            <input type="text" name="color" id="color" v-model="driver.color" placeholder="Color"
                   class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none">
          </div>
          <div class="mt-2">
            <Error class="mt-2" :message="errors.licence_plate"></Error>
            <input type="text" name="licence_plate" v-model="driver.licence_plate" id="licence_plate"
                   placeholder="Licence Plate #"
                   class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none">
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
          <button type="submit"

                  class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">Continue</button>
        </div>
      </div>
    </form>
  </div>
</template>
