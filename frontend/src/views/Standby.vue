<script setup>
import {useLocationStore} from "@/stores/location.js";

const locationStore = useLocationStore();

const handleConfirmDrive = () => {

}

const handleDeclineDrive = () => {

}


</script>

<template>
  <div class="pt-16">
    <h1 class="text-3xl font-semibold mb-4">Customer want to drive!</h1>
    <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
      <div class="bg-white px-4 py-5 sm:p-6">
        <div>
          <GMapMap :zoom="11" :center="locationStore.destination.geometry"
                   style="width: 100%; height: 256px;"
                   ref="gMap">
            <GMapMarker :position="locationStore.destination.geometry"></GMapMarker>
          </GMapMap>
        </div>
        <div class="mt-2 text-right px-3">
          <p class="text-xl">Drive to <strong>{{ locationStore.destination.name }}</strong></p>
        </div>
      </div>
      <div class="flex justify-between bg-gray-50 px-4 py-3 text-right sm:px-6">
        <button
            @click.prevent="handleConfirmDrive"
            class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">
          Let's go!
        </button>
        <button
            @click.prevent="handleDeclineDrive"
            class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">
          Let's go!
        </button>
      </div>
    </div>
  </div>
</template>
