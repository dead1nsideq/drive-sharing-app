<script setup>

import {markRaw, onMounted, ref} from "vue";
import {useLocationStore} from "@/stores/location.js";

const props = defineProps({
  trip: {
    type: Object,
    required: true
  }
})


const locationStore = useLocationStore();

const gMap = ref(null);

defineEmits(
    ['start']
)

onMounted( async () => {

  await locationStore.updateCurrentLocation();

  setTimeout(initMapDirections, 2000)
})

const initMapDirections = () => {
  console.log("INIT MAP DIR")
  console.log(locationStore.current.geometry)
  console.log(props.trip.origin)
  const test = {
    lat: props.trip.origin.lat + 0.000100,
    lng: props.trip.origin.lng + 0.000100
  }
  console.log(test)
  gMap.value.$mapPromise.then((mapObject) => {

    // подумать откуда брать координаты, вроде работает но еще раз пересмотреть концепцию
    // локатион стора и трип стора то есть сначала же водитель едет забирать пассажира
    // потом они уже едут в место назначения
    let originPoint = new google.maps.LatLng(locationStore.current.geometry),
        destinationPoint = new google.maps.LatLng(test),
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
</script>
<template>
  <h1 class="text-3xl font-semibold mb-4"> {{ trip.id }} wants to drive </h1>
  <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
    <div class="bg-white px-4 py-5 sm:p-6">
      <div>
        <GMapMap :zoom="11" :center="trip.destination"
                 style="width: 100%; height: 256px;"
                 ref="gMap">
          <GMapMarker :position="trip.destination"></GMapMarker>
        </GMapMap>
      </div>
      <div class="mt-2 text-right px-3">
        <p class="text-xl">Drive to <strong>{{ trip.destination_name }}</strong></p>
      </div>
    </div>
    <div class="flex justify-between bg-gray-50 px-4 py-3 text-right sm:px-6">
      <button
          @click="$emit('start', trip.id)"
          class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">
        Let's go!
      </button>
    </div>
  </div>
</template>
