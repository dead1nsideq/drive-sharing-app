<script setup>
import {useLocationStore} from "@/stores/location.js";

import {onMounted, reactive, ref} from "vue";

const locationStore = useLocationStore();

const gMap = ref(null);
let directionsService = null;
let directionsRenderer = null;
const destination = reactive({
  name: '',
  formatted_address: '',
  geometry: {
    lat: null,
    lng: null
  }
});

onMounted( () => {
  locationStore.updateCurrentLocation()

  // якщо сторінка не повінстью загрузилась то directionsService буде null і викине помилку
  gMap.value.$mapPromise.then((mapObject) => {
    console.log('before')
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({
      map: mapObject
    })
    console.log('after')
  })
})

// shift it to component
const showErrorMessage = ref(false);
const handleDestinationChanged = (e) => {
  console.log(e);
  if (e.geometry === undefined) {
    showErrorMessage.value = true
    destination.name = '';
    destination.formatted_address = '';
    destination.geometry.lat = null;
    destination.geometry.lng = null;
  } else {
    destination.name = e.name;
    destination.formatted_address = e.formatted_address;
    destination.geometry.lat = e.geometry.location.lat();
    destination.geometry.lng = e.geometry.location.lng();
    showErrorMessage.value = false


    calculateAndDisplayRoute(directionsService, directionsRenderer)
  }
}


function calculateAndDisplayRoute(directionsService, directionsRenderer) {
  directionsService
      .route({
        origin: locationStore.current.geometry,
        destination: destination.geometry,
        travelMode: google.maps.TravelMode.DRIVING,
      })
      .then((response) => {
        directionsRenderer.setDirections(response);
      })
      .catch((e) => window.alert("Directions request failed due to " + e.status));
}

const handleSelectLocation = () => {
  console.log(destination);
  showErrorMessage.value = destination.geometry.lat === null && destination.geometry.lng === null;
}

const test = () => {
  console.log('test')
}

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
                     @place_changed="handleDestinationChanged"
                     class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none">
              </GMapAutocomplete>
            </div>
          </div>
          <div v-show="destination.geometry.lat !== null && destination.geometry.lng !== null">
            <GMapMap style="width: 100%; height: 256px;" :zoom="11" ref="gMap" :center="locationStore.current.geometry">

            </GMapMap>
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
