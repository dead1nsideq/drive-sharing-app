<script setup>

defineProps({
  trip: {
    type: Object,
    required: true
  }
})

defineEmits(
    ['start']
)


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
