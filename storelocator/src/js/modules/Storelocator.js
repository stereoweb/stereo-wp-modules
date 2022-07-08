import { module } from 'modujs';
import Vue from 'vue'
import * as VueGoogleMaps from 'vue2-google-maps'

export default class extends module {
    constructor(m) {
        
        super(m);
       
    }

    init() {
        Vue.use(VueGoogleMaps, {
            load: {
                key: this.el.dataset.apikey,
                libraries: 'places',
            },
            installComponents: true,
        })

        this.vue = new Vue({
            el: this.el,
            data: {
                stores: [],
                center: {
                    lat: this.el.dataset.lat,
                    lng: this.el.dataset.lng,
                },
                currentStore: {}
            }, methods: {
                init() {
                    this.getStores(this.center);
                },
                setCurrentStore(store) {
                    this.center.lat = store.lat;
                    this.center.lng = store.lng;
                    this.currentStore = store;
                },
                locateMe() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(position => {
                            this.center.lat = position.coords.latitude;
                            this.center.lng = position.coords.longitude;
                            this.getStores(this.center);
                        });
                    }
                },
                getStores(position) {
                    fetch('/wp-json/storelocator/v1/markers?lat='+position.lat+'&lng='+position.lng, {'method':'get'}).then(response => response.json()).then(data => {
                        this.stores = data.stores;
                    });
                }
            },
            filters: {
                rounded(value) {
                    return Math.round(value/100)*100;
                }
            },
            mounted() {
                this.init();
            }
            
        })

    }
    destroy() {
        super();
        this.vue.$destroy();
    }
}
