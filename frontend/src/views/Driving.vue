<script setup>

import {useTripStore} from "@/stores/trip.js";
import {useLocationStore} from "@/stores/location.js";
import {computed, onMounted, onUnmounted, ref} from "vue";
import axios from "axios";
import router from "@/router/index.js";

const tripStore = useTripStore();
const locationStore = useLocationStore();

const intervalRef = ref(null);
const distance = ref(null);

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
      destinationPoint = new google.maps.LatLng(tripStore.origin),
      latLngBounds = new google.maps.LatLngBounds()

  latLngBounds.extend(originPoint)
  latLngBounds.extend(destinationPoint)

  mapObject.fitBounds(latLngBounds)
}

function handleCancelTrip() {
  axios.delete(`/trip/${tripStore.id}/cancel`).then((res) => {
        console.log(res.data)
        tripStore.resetState()
        router.push({
          name: 'home'
        })
      }
  ).catch((err) => console.error(err))
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

const calculateDistance = () => {
  // Создание объектов координат
  const origin = new google.maps.LatLng(locationStore.current.geometry); // Нью-Йорк
  const destination = new google.maps.LatLng(tripStore.origin); // Лос-Анджелес

// Расчет расстояния между точками
  const distance = google.maps.geometry.spherical.computeDistanceBetween(origin, destination);
  return distance;
}

const driverArrived = computed(() => {
    return distance.value !== null && distance.value < 10;
})

onMounted(() => {
  gMap.value.$mapPromise.then((mapObject) => {
    updateMapBounds(mapObject)

    intervalRef.value = setInterval(async () => {
      // update the driver's current position and update map bounds
      await locationStore.updateCurrentLocation()

      // update the driver's position in the database
      broadcastDriverLocation()

      updateMapBounds(mapObject)

      console.log(distance.value)
      distance.value = calculateDistance();
      console.log(distance.value)
      // set to 10 sec
    }, 1000000)
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
            <GMapMap :zoom="14" :center="{ lat: +locationStore.current.geometry.lat, lng: +locationStore.current.geometry.lng}" ref="gMap"
                     style="width:100%; height: 256px;">
              <GMapMarker :position="{ lat: +locationStore.current.geometry.lat, lng: +locationStore.current.geometry.lng}" :icon="currentIcon" />
              <GMapMarker :position="{ lat: +tripStore.origin.lat, lng: +tripStore.origin.lng}" :icon="destinationIcon" />
            </GMapMap>
          </div>
        <div class="mt-2">
            <p class="text-xl">Going to <strong>pick up a passenger</strong></p>
        </div>
        </div>
        <div class="flex justify-between bg-gray-50 px-4 py-3 sm:px-6">
            <button class="text-left" @click.prevent="handleCancelTrip">Cancel</button>
            <button v-if="driverArrived" class="text-right" @click.prevent="handleCancelTrip">Passenger got in</button>
        </div>
      </div>
    </div>
  </div>
</template>
