<template>
  <div class="q-pa-lg">
    <q-toolbar class="bg-primary rounded-borders">
      <q-btn flat round dense icon="search" class="q-mr-sm" />

      <q-toolbar-title>Choose your location for finding posts</q-toolbar-title>

    </q-toolbar>
  </div>
  <div class="q-pa-lg">
    <MapView v-model:latitude="lat_long.latitude" v-model:longitude="lat_long.longitude" :state="'update'">
      Choose your location
    </MapView>
    <q-btn class="bg-primary full-width" @click="find()">Search</q-btn>
  </div>
  <div class="q-pa-md row items-start q-gutter-md justify-center">
    <transition-group appear enter-active-class="animated slideInDown" leave-active-class="">
      <div v-for="(post, index) in posts" :key="index">
        <q-card class="my-card">
          <router-link :to="{
            name: 'postDetail',
            params: { id: post.id, name: post.title },
          }">
            <q-img :src="post.img" width="100%" height="300px" />
          </router-link>
          <q-card-section>
            <q-btn fab color="light-blue-8" icon="place" class="absolute"
              style="top: 0; right: 12px; transform: translateY(-50%)" @click="fullMap(post.latitude, post.longitude)" />
            <div class="row no-wrap items-center">
              <div class="col text-h6 ellipsis">
                <router-link style="color: inherit; text-decoration: none" :to="{
                  name: 'postDetail',
                  params: { id: post.id, name: post.title },
                }">
                  {{ post.title }}
                </router-link>
              </div>
              <div class="col-auto text-grey text-caption q-pt-md row no-wrap items-center"></div>
            </div>
            Lat: <span class="text-info" v-location="post.latitude"></span> Long:
            <span class="text-info q-ma-xs" v-location="post.longitude"></span>
          </q-card-section>
          <q-card-section class="q-pt-none">
            <div class="text-subtitle1">
              {{ post.username }}
            </div>
            <div class="text-caption text-grey">
              {{ post.description.substring(0, 200) }} ...
            </div>
          </q-card-section>
          <q-separator />
          <q-card-actions>
            <q-btn color="light-blue-8" icon-right="favorite" :label="`Like (${post.upVoteCount})`"
              @click="like(post.id)" />
          </q-card-actions>
        </q-card>
      </div>
    </transition-group>

    <q-dialog v-model="fullMapView" persistent :maximized="maximizedToggle" transition-show="slide-up"
      transition-hide="slide-down">
      <q-card class="bg-primary text-white">
        <q-bar>
          <q-space />
          <q-btn dense flat icon="close" v-close-popup>
            <q-tooltip class="bg-white text-primary">Close</q-tooltip>
          </q-btn>
        </q-bar>

        <map-view class="full-width full-height" :latitude="mapVariable.lat" :longitude="mapVariable.long"
          :state="'view'"></map-view>
      </q-card>
    </q-dialog>
  </div>
  <div class="q-gutter-md">
    <q-pagination class="d-flex justify-center" v-model="pagination.current_page" :max="pagination.last_page"
      direction-links push limit="5" color="primary" active-design="push" active-color="orange" boundary-numbers
      :max-pages="10" @update:model-value="pagination_state" />
  </div>
</template>
<script lang="ts" setup>
import MapView from 'src/components/map/mapView.vue';
import { ref } from 'vue';
import { Post } from 'src/models/post';
import { useQuasar } from 'quasar';

const lat_long = ref({
  latitude: <number>37.28,
  longitude: <number>49.6,
});


const pagination = ref({
  current_page: 3,
  last_page: 4,
  per_page: 4,
});

const pagination_state = () => {
  Post.searchPost(
      pagination.value.current_page,
      pagination.value.per_page,
      lat_long.value.latitude,
      lat_long.value.longitude
    ).then((response) => {
    posts.value = response.posts;
    pagination.value.current_page = response.page_data.current_page;
    pagination.value.last_page = response.page_data.last_page;
  });
};
const posts = ref();

const refresh = () => {
  Post.searchPost(
      1,
      pagination.value.per_page,
      lat_long.value.latitude,
      lat_long.value.longitude
    ).then((response) => {
    posts.value = response.posts;
    pagination.value.current_page = response.page_data.current_page;
    pagination.value.last_page = response.page_data.last_page;
  });
};
refresh();

const $q = useQuasar();

const mapVariable = ref({
  lat: <number>0,
  long: <number>0,
  state: <string>'view',
});
const fullMapView = ref(false);
const fullMap = (lat: number, long: number) => {
  mapVariable.value.lat = lat;
  mapVariable.value.long = long;
  fullMapView.value = true;
};
const maximizedToggle = ref(true);

const like = (id: number) => {
  Post.likePost(id).then((response) => {
    if (response.status == 200) {
      refresh();
      setTimeout(() => {
        $q.notify({
          message: 'Your Like Submitted.',
          color: 'positive',
          position: 'bottom-right',
        });
      }, 1000);
    }
  });
};
const vLocation = {
  beforeMount(el, binding) {
    el.innerText = parseFloat(binding.value).toFixed(2);
  },
  updated(el, binding) {
    el.innerText = parseFloat(binding.value).toFixed(2);
  },
};
const find = () => {
  Post.searchPost(
      pagination.value.current_page,
      pagination.value.per_page,
      lat_long.value.latitude,
      lat_long.value.longitude
    ).then((response) => {
    posts.value = response.posts;
    pagination.value.current_page = response.page_data.current_page;
    pagination.value.last_page = response.page_data.last_page;
  });
}
</script>

<style lang="sass" scoped>
.my-card
  width: 100%
  max-width: 300px
</style>
