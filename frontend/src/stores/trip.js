// TODO сохранять поездку что бы не терялась в процессе,
//  возможно делать это в locationStore,смотреть
//  если ли сейчас активная поезда у пользователя или у водителя
// mb add driver location and user location

import {defineStore} from "pinia";
import {reactive, ref} from "vue";

export const useTripStore = defineStore('trip', () => {
    const id = ref(null);
    const user_id = ref(null);

    const origin = reactive({
        lat: null,
        lng: null
    })

    const destination = reactive({
        lat: null,
        lng: null
    })

    const destination_name = ref('')

    const driver_location = reactive({
        lat: null,
        lng: null
    })

    const driver = reactive({
        id: null,
        year: null,
        make: null,
        model: null,
        licence_plate: null,
        user: {
            id: null,
            name: null,
        }
    })

    const status = ref('not_started')

    function resetState() {
        id.value = null
        user_id.value = null
        origin.lat = null
        origin.lng = null
        destination.lat = null
        destination.lng = null
        destination_name.value = ''

        driver.id = null
        driver.year = null
        driver.make = null
        driver.model = null
        driver.licence_plate = null
        driver.user.name = null
        driver.user.id = null

        status.value = 'not_started'
    }

    function setState(data) {
        id.value = data.id || null;
        user_id.value = data.user_id || null;
        origin.lat = data.origin ? +data.origin.lat : null;
        origin.lng = data.origin ? +data.origin.lng : null;
        destination.lat = data.destination ? +data.destination.lat : null;
        destination.lng = data.destination ? +data.destination.lng : null;
        destination_name.value = data.destination_name || null;
        driver_location.lat = data.driver_location ? +data.driver_location.lat : null;
        driver_location.lng = data.driver_location ? +data.driver_location.lng : null;

        driver.id = data.driver ? data.driver.id : null;
        driver.year = data.driver ? data.driver.year : null;
        driver.make = data.driver ? data.driver.make : null;
        driver.model = data.driver ? data.driver.model : null;
        driver.licence_plate = data.driver ? data.driver.licence_plate : null;
        driver.user.name = data.driver && data.driver.user ? data.driver.user.name : null;
        driver.user.id = data.driver && data.driver.id ? data.driver.user.id : null;

        status.value = data.status || null;
    }

    async function initTripCheck() {
        if (!id.value) {
            await axios.get(`/trip`)
                .then((res) => {
                    const data = res.data;
                    console.log(data)
                    setState(res.data);
                })
                .catch((err) => console.error(err));
        }
    }




    return { id, user_id, origin, destination, destination_name ,driver_location ,driver,status, resetState ,initTripCheck }
})