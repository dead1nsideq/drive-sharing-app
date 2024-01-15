<script setup>

import {useTripStore} from "@/stores/trip.js";
import {useLocationStore} from "@/stores/location.js";
import {onMounted, onUnmounted, ref} from "vue";
import axios from "axios";

const tripStore = useTripStore();
const locationStore = useLocationStore();

const intervalRef = ref(null);

const currentIcon = {
  url: 'https://openmoji.org/data/color/svg/1F698.svg',
  scaledSize: {
    width: 32,
    height: 32
  }
}

const destinationIcon = {
  url: 'https://openmoji.org/data/color/svg/1F920.svg',
  scaledSize: {
    width: 24,
    height: 24
  }
}

const updateMapBounds = (mapObject) => {
  let originPoint = new google.maps.LatLng(locationStore.current.geometry),
      destinationPoint = new google.maps.LatLng(locationStore.destination.geometry),
      latLngBounds = new google.maps.LatLngBounds()

  latLngBounds.extend(originPoint)
  latLngBounds.extend(destinationPoint)

  mapObject.fitBounds(latLngBounds)
}

const broadcastDriverLocation = () => {
  axios.post(`/trip/${tripStore.id}/location`, {
    driver_location: locationStore.current.geometry
  })
      .then((response) => {})
      .catch((error) => {
        console.error(error)
      })
}

onMounted(() => {
  gMap.value.$mapPromise.then((mapObject) => {
    updateMapBounds(mapObject)

    intervalRef.value = setInterval(async () => {
      // update the driver's current position and update map bounds
      await locationStore.updateCurrentLocation()

      // update the driver's position in the database
      broadcastDriverLocation()

      updateMapBounds(mapObject)
    }, 5000)
  })
})

onUnmounted(() => {
  clearInterval(intervalRef.value)

  intervalRef.value = null
})

const gMap = ref(null)
</script>

<template>
  <div class="pt-16">
    <h1 class="text-3xl font-semibold mb-4">Waiting for driver...</h1>
    <div>
      <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
        <div class="bg-white px-4 py-5 sm:p-6">
          <div>
            <GMapMap :zoom="14" :center="locationStore.current.geometry" ref="gMap"
                     style="width:100%; height: 256px;">
              <GMapMarker :position="locationStore.current.geometry" :icon="currentIcon" />
              <GMapMarker :position="locationStore.destination.geometry" :icon="destinationIcon" />
            </GMapMap>
          </div>
        <div class="mt-2">
            <p class="text-xl">Going to <strong>pick up a passenger</strong></p>
        </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">

        </div>
      </div>
    </div>
  </div>
</template>
