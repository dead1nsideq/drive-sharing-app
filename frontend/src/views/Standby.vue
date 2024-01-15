<script setup>
import {useLocationStore} from "@/stores/location.js";
import Loader from "@/components/Loader.vue";
import {onMounted, ref} from "vue";
import {useTripStore} from "@/stores/trip.js";
import axios from "axios";
import router from "@/router/index.js";

// const locationStore = useLocationStore();
const tripStore = useTripStore();
const locationStore = useLocationStore();
const gMap = ref(null);

onMounted(async () => {
  await locationStore.updateCurrentLocation();

  window.Echo.channel('drivers').listen('TripCreated', (e) => {
    // tripStore.id = e.trip.id
    // tripStore.user_id = e.trip.user_id
    // tripStore.origin = e.trip.origin
    // tripStore.destination = e.trip.destination
    // tripStore.destination_name = e.trip.destination_name
    tripStore.$patch(e.trip)
    console.log(e.trip)

    setTimeout(initMapDirections, 2000)
  })
})

const initMapDirections = () => {
  gMap.value.$mapPromise.then((mapObject) => {

    let originPoint = new google.maps.LatLng(tripStore.origin),
        destinationPoint = new google.maps.LatLng(tripStore.destination),
        directionsService = new google.maps.DirectionsService,
        directionsDisplay = new google.maps.DirectionsRenderer({
          map: mapObject
        })

    directionsService.route({
      origin: originPoint,
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
}

const handleConfirmDrive = () => {
    axios.post(`trip/${tripStore.id}/accept`, {
        driver_location: locationStore.current.geometry
    }).then((response) => {
        locationStore.$patch({
          destination: {
            name: 'Passenger',
            geometry: response.data.origin
          }
        })

        router.push({
          name: 'driving'
        })
    }).catch((error) => console.error(error))
}

const handleDeclineDrive = () => {
    tripStore.resetState();
}


</script>

<template>
  <div class="pt-16">
    <div v-if="!tripStore.id">
      <h1 class="text-3xl font-semibold mb-4">Waiting for ride request...</h1>
      <div class="mt-8 flex justify-center">
          <Loader></Loader>
      </div>
    </div>
    <div v-else>
      <h1 class="text-3xl font-semibold mb-4">Customer want to drive!</h1>
      <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
        <div class="bg-white px-4 py-5 sm:p-6">
          <div>
            <GMapMap :zoom="11" :center="tripStore.destination"
                     style="width: 100%; height: 256px;"
                     ref="gMap">
              <GMapMarker :position="tripStore.destination"></GMapMarker>
            </GMapMap>
          </div>
          <div class="mt-2 text-right px-3">
            <p class="text-xl">Drive to <strong>{{ tripStore.destination.name }}</strong></p>
          </div>
        </div>
        <div class="flex justify-between bg-gray-50 px-4 py-3 text-right sm:px-6">
          <button
              @click.prevent="handleDeclineDrive"
              class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">
            Decline
          </button>
          <button
              @click.prevent="handleConfirmDrive"
              class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">
            Let's go!
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
