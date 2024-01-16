<script setup>
import {useLocationStore} from "@/stores/location.js";
import {useTripStore} from "@/stores/trip.js";
import {onBeforeMount, onMounted, ref} from "vue";
import {useAuthStore} from "@/stores/auth.js";
import router from "@/router/index.js";

const locationStore = useLocationStore();
const tripStore = useTripStore();
const authStore = useAuthStore();

const title = ref('Waiting for driver...')
const message = ref('When a driver accepts the trip, their info will appear here')

const currentIcon = {
  url: 'https://openmoji.org/data/color/svg/1F698.svg',
  scaledSize: {
    width: 32,
    height: 32
  }
}

onBeforeMount(() => {

  console.log('Mog')
})

onMounted(() => {


  window.Echo.channel(`passenger_${authStore.state.user_id}`).listen('TripAccepted', (e) => {
      console.log('from broadcast', e)
      tripStore.$patch(e.trip)
      title.value = 'A driver is on the way'
      message.value = `${e.trip.driver.user.name} is coming in a ${e.trip.driver.year} ${e.trip.driver.color} ${e.trip.driver.make} ${e.trip.driver.model} with a license plate #${e.trip.driver.licence_plate}`
  })
})

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

</script>

<template>
  <div class="pt-16">
    <h1 class="text-3xl font-semibold mb-4"> {{ title }} </h1>
    <div>
      <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
          <div class="bg-white px-4 py-5 sm:p-6">
             <div>
               <GMapMap :center="{lat: +tripStore.destination.lat, lng: +tripStore.destination.lng}" :zoom="14"
                        ref="gMap" style="width: 100%; height: 256px;">
                 <GMapMarker :position="locationStore.current.geometry" :icon="currentIcon" />
               </GMapMap>
             </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
            <div class="flex justify-between">
              <span> {{ message }} </span>
              <button class="bg-gray-600" type="submit" @click="handleCancelTrip">Delete trip</button>
            </div>
          </div>
      </div>
    </div>
  </div>
</template>
