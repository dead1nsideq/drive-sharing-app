<script setup>
import {useLocationStore} from "@/stores/location.js";
import Loader from "@/components/Loader.vue";
import {onMounted, ref} from "vue";
import {useTripStore} from "@/stores/trip.js";
import axios from "axios";
import router from "@/router/index.js";
import TripComponent from "@/components/TripComponent.vue";

// const locationStore = useLocationStore();
const tripStore = useTripStore();
const locationStore = useLocationStore();

const trips = ref(null)

const gMap = ref(null);

onMounted(async () => {
  await locationStore.updateCurrentLocation();

  axios.get('/driver/trips').then((res) => {
    console.log(res.data)
    trips.value = res.data
  }).catch((err) => console.error(err))

  window.Echo.channel('drivers').listen('TripCreated', (e) => {
    // tripStore.id = e.trip.id
    // tripStore.user_id = e.trip.user_id
    // tripStore.origin = e.trip.origin
    // tripStore.destination = e.trip.destination
    // tripStore.destination_name = e.trip.destination_name
    // tripStore.$patch(e.trip)
    trips.value.push(e.trip)
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

const handleConfirmDrive = (trip) => {
    // TODO сделать комнонентом карту,передавать туда в пропс id поездки и при нажатии кнопашки отправлять єто айді
    axios.post(`trip/${trip}/accept`, {
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

function handleStart(tripId) {
  handleConfirmDrive(tripId)
}

</script>

<template>
  <div class="pt-16">
    <div v-if="!trips">
      <h1 class="text-3xl font-semibold mb-4">Waiting for ride request...</h1>
      <div class="mt-8 flex justify-center">
          <Loader></Loader>
      </div>
    </div>
    <div v-else v-for="trip in trips">
        <TripComponent :trip="trip" @start="handleStart"></TripComponent>
    </div>
  </div>
</template>
