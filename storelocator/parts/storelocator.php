<section class="o-section">
    <div class="o-container">
        <div class="c-storelocator" data-module-storelocator data-lat="46.341148" data-lng="-72.54608" data-apikey="<?php echo getenv("GMAP_API_KEY");?>">
            <form class="o-form c-storelocator__form">
                <div class="o-form-group c-storelocator__formgroup">
                    <div class="o-form-group__item c-storelocator__formgroupitem">
                        <label class="o-label sr-only c-storelocator__label" for="sl-address">Votre adresse</label>
                        <input class="o-input c-storelocator__input" type="text" id="sl-address" name="Votre adresse" placeholder="Votre adresse" required>
                    </div>
                </div>
                <div class="o-form__button c-storelocator__formbuttons">
                    <button class="o-button c-storelocator__button" type="submit">
                        Rechercher
                    </button>
                    <button @click="locateMe()" class="o-button c-storelocator__button" type="button">
                        Localiser-moi
                    </button>
                </div>
            </form>
            <div class="o-row">
                <div class="o-col w-1/3">
                    <div class="c-storelocator__list">
                        <div v-for="item in stores" class="c-storelocator__item" :class="{'is-current': currentStore.ID == item.ID}" @click="setCurrentStore(item)">
                            <h3 class="c-storelocator__title">{{item.title}}</h3>
                            <address class="c-storelocator__address">
                                {{item.address}}<template v-if="item.address2"><br>, {{item.address2}}</template>
                                <br>{{item.city}}, {{item.postalcode}}
                            </address>
                            <div class="c-storelocator__distance">
                                {{item.distance | rounded}} km
                            </div>
                        </div>
                    </div>
                </div>
                <div class="o-col w-2/3">
                    <div class="c-storelocator__mapwrap">
                        <GmapMap class="c-storelocator__map"
                            :center="center"
                            :zoom="16"
                            map-type-id="terrain"
                            
                            >
                            <GmapMarker
                                :key="index"
                                v-for="(m, index) in stores"
                                :position="m.position"
                                :clickable="true"
                                :draggable="true"
                                @click="center=m.position;showInfo(m);"
                            />
                        </GmapMap>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>