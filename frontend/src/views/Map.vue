<script setup>

import {useLocationStore} from "@/stores/location.js";
import {onBeforeMount, onBeforeUnmount, onMounted, ref} from "vue";
import router from "@/router/index.js";

const locationStore = useLocationStore();
const gMap = ref(null);

const handleConfirmTrip = () => {
    axios.post('/trip', {
      origin: locationStore.current.geometry,
      destination: locationStore.destination.geometry,
      destination_name: locationStore.destination.name
    }).then((response) => {
      console.log(response)
      router.push({
        name: 'trip'
      })
    }).catch((error) => {
      console.error(error)
    })
}

onMounted(async () => {
  // if (locationStore.destination.name === ''
  //     && locationStore.destination.geometry.lat === null
  //     && locationStore.destination.geometry.lng === null) {
  //   router.push({
  //     name: 'location'
  //   })
  // }

  await locationStore.updateCurrentLocation()
  // navigator.geolocation.getCurrentPosition((success) => {
  //   console.log(success)
  // }, (error) => {
  //   console.log(error)
  // });

  // draw a path on the map
  gMap.value.$mapPromise.then((mapObject) => {

    let currentPoint = new google.maps.LatLng(locationStore.current.geometry),
        destinationPoint = new google.maps.LatLng(locationStore.destination.geometry),
        directionsService = new google.maps.DirectionsService,
        directionsDisplay = new google.maps.DirectionsRenderer({
          map: mapObject
        })

    directionsService.route({
      origin: currentPoint,
      destination: destinationPoint,
      avoidTolls: false,
      avoidHighways: false,
      travelMode: google.maps.TravelMode.DRIVING
    }, (res, status) => {
      if (status === google.maps.DirectionsStatus.OK) {
        directionsDisplay.setDirections(res)
      } else {
        console.error(status)
      }
    })
  })
})
</script>

<template>
  <div class="pt-16">
    <h1 class="text-3xl font-semibold mb-4">Here's your trip</h1>
    <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
      <div class="bg-white px-4 py-5 sm:p-6">
        <div>
          <GMapMap :zoom="11" :center="locationStore.destination.geometry"
                   style="width: 100%; height: 256px;"
                   ref="gMap">
            <GMapMarker :position="locationStore.destination.geometry"></GMapMarker>
          </GMapMap>
        </div>
        <div class="mt-2">
          <p class="text-xl">Going to <strong>{{ locationStore.destination.name }}</strong></p>
        </div>
      </div>
      <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
        <button
            @click.prevent="handleConfirmTrip"
            class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">
          Let's go!
        </button>
      </div>
    </div>
  </div>
</template>
