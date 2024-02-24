import {defineStore} from "pinia";
import {reactive} from "vue";


export const getUserLocation = async () => {
    return new Promise((res,rej) => {
        navigator.geolocation.getCurrentPosition(res,rej)
    })
}
export const useLocationStore = defineStore('location',() => {

    const current =reactive({
        geometry: {
            lat: null,
            lng: null
        }
    })

    const updateCurrentLocation = async () => {
        const userLocation = await getUserLocation();
        // current.geometry = {
        //     lat: userLocation.coords.latitude,
        //     lng: userLocation.coords.longitude,
        // }
        current.geometry.lat = userLocation.coords.latitude
        current.geometry.lng = userLocation.coords.longitude
    }


    return { current , updateCurrentLocation }
})


/*
 Может быть тоже трекать юзер локейшон и менять его если он прошел каждые 5 метров (currentLoc - originLoc) > 5 до того
 пока он не сядет в машину (после этого окончательно перестать трекать его локейшон)

 убрать locationStore, объединить его с tripStore продумать отдельную логику для водителя и юзера

 то есть водитель сначала еде к юзеру, потом вместе едут куда-то, поездка окон
 */