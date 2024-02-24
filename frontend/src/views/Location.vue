<script setup>
import {useLocationStore} from "@/stores/location.js";
import router from "@/router/index.js";
import {ref} from "vue";

const locationStore = useLocationStore();
const showErrorMessage = ref(false);
const handleLocationChanged = (e) => {
  console.log(e);
  locationStore.$patch({
    destination: {
      name: e.name,
      address: e.formatted_address,
      geometry: {
        lat: e.geometry.location.lat(),
        lng: e.geometry.location.lng()
      }
    }
  })
}

const handleSelectLocation = () => {
  if (locationStore.destination.name !== '') {
    showErrorMessage.value = false
    router.push({
      name: 'map'
    })
  } else {
    showErrorMessage.value = true
  }
}

// можно поставить const destination = ref('') и если destination.value !== '' то нарисовать путь до это локации на карте

</script>

<template>

  <div class="pt-16">
      <h1 class="text-3xl font-semibold mb-4">Where are we going?</h1>
      <form action="#">
        <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
          <div class="bg-white px-4 py-5 sm:p-6">
            <div v-if='showErrorMessage' class="text-sm mb-2 text-red-500">
              Please select location
            </div>
            <div>
              <GMapAutocomplete name="location"
                     id="location" placeholder="My destination"
                     @place_changed="handleLocationChanged"
                     class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none">
              </GMapAutocomplete>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
            <button
                    @click.prevent="handleSelectLocation"
                    type="submit"
                    class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">
              Find a ride
            </button>
          </div>
        </div>
      </form>
    </div>
</template>
