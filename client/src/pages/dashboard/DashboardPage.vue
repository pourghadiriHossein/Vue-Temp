<template>
  <div class="q-pa-sm">
    <q-carousel
      v-model="slide"
      transition-prev="slide-right"
      transition-next="slide-left"
      swipeable
      animated
      control-color="amber"
      navigation
      padding
      arrows
      height="300px"
      class="shadow-2 rounded-borders"
    >
      <q-carousel-slide
      v-for="(item, index) in top_posts" :key="index"
      :name="index + 1"
      class="column no-wrap">
        <div
          class="row fit justify-start items-center q-gutter-xs q-col-gutter no-wrap"
        >
          <div class="rounded-borders col-6 full-height">
            <map-view
              class="full-width full-height"
              :latitude="item.latitude"
              :longitude="item.longitude"
              :state="'view'"
            ></map-view>
          </div>
          <div class="rounded-borders col-6 full-height">
            <div class="full-width full-height rel">
              <q-img
                :src="`http://127.0.0.1:8000/${item.media[0].url}`"
                class="full-width full-height detail"
              />
              <div class="cover"></div>
              <div class="overlay">
                <p class="overlay-title">{{item.title}}</p>
                <p class="overlay-owner">{{item.user.name}}</p>
                <p class="overlay-text">
                  {{item.description.substring(0, 100)}} ...
                </p>
              </div>
            </div>
          </div>
        </div>
      </q-carousel-slide>
    </q-carousel>
  </div>
  <div class="q-pa-md row items-start q-gutter-md justify-center">
    <q-card class="my-card" v-for="post in posts" :key="post">
      <q-img :src="post.img" width="100%" height="300px" />
      <q-card-section>
        <q-btn
          fab
          color="light-blue-8"
          icon="place"
          class="absolute"
          style="top: 0; right: 12px; transform: translateY(-50%)"
          @click="fullMap(post.latitude, post.longitude)"
        />
        <div class="row no-wrap items-center">
          <div class="col text-h6 ellipsis">
            {{ post.title }}
          </div>
          <div
            class="col-auto text-grey text-caption q-pt-md row no-wrap items-center"
          ></div>
        </div>
        <span class="text-info" v-lat-directive="post.latitude"
          >Lat: {{ vLat }}</span
        >
        <span class="text-info q-ma-xs" v-long-directive="post.longitude"
          >Long: {{ vLong }}</span
        >
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
        <q-btn
          color="light-blue-8"
          icon-right="favorite"
          :label="`Like (${post.upVoteCount})`"
          @click="like(post.id)"
        />
      </q-card-actions>
    </q-card>
    <q-dialog
      v-model="fullMapView"
      persistent
      :maximized="maximizedToggle"
      transition-show="slide-up"
      transition-hide="slide-down"
    >
      <q-card class="bg-primary text-white">
        <q-bar>
          <q-space />
          <q-btn dense flat icon="close" v-close-popup>
            <q-tooltip class="bg-white text-primary">Close</q-tooltip>
          </q-btn>
        </q-bar>

        <map-view
          class="full-width full-height"
          :latitude="mapVariable.lat"
          :longitude="mapVariable.long"
          :state="'view'"
        ></map-view>
      </q-card>
    </q-dialog>
  </div>
  <div class="q-gutter-md">
    <q-pagination
      class="d-flex justify-center"
      v-model="pagination.current_page"
      :max="pagination.last_page"
      direction-links
      push
      color="primary"
      active-design="push"
      active-color="orange"
      boundary-numbers
      :max-pages="pagination.current_page"
      @update:model-value="pagination_state"
    />
  </div>
</template>
<script lang="ts" setup>
import MapView from 'src/components/map/mapView.vue';
import { ref } from 'vue';
import { Post } from 'src/models/post';
import { useQuasar } from 'quasar';

const slide = ref(1);

const pagination = ref({
  current_page: 3,
  last_page: 4,
  per_page: 2,
});

const pagination_state = () => {
  Post.allPostInDashboard(
    pagination.value.current_page,
    pagination.value.per_page
  ).then((response) => {
    posts.value = response.posts;
    pagination.value.current_page = response.page_data.current_page;
    pagination.value.last_page = response.page_data.last_page;
  });
};
const top_posts = ref();
const posts = ref();

const refresh = () => {
  Post.allPostInDashboard(1, pagination.value.per_page).then((response) => {
    posts.value = response.posts;
    pagination.value.current_page = response.page_data.current_page;
    pagination.value.last_page = response.page_data.last_page;
    top_posts.value = response.top_post;
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
const vLat = ref(0);
const vLong = ref(0);
const vLatDirective = {
  mounted(el, binding) {
    vLat.value = parseFloat(binding.value).toFixed(2);
  },
  beforeUpdate(el, binding) {
    vLat.value = parseFloat(binding.value).toFixed(2);
  },
};
const vLongDirective = {
  mounted(el, binding) {
    vLong.value = parseFloat(binding.value).toFixed(2);
  },
  beforeUpdate(el, binding) {
    vLong.value = parseFloat(binding.value).toFixed(2);
  },
};
</script>

<style lang="sass" scoped>
.my-card
  width: 100%
  max-width: 300px
</style>
<style>
.rel {
  position: relative;
}
.cover {
  position: absolute;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  left: 0;
  top: 0;
  z-index: 1;
}
.overlay {
  position: absolute;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  z-index: 2;
  overflow: hidden;
  align-items: flex-start;
}
.overlay-title {
  font-size: 24px;
  font-weight: 1000;
  color: white;
  padding: 30px 20px 0;
}
.overlay-owner {
  font-size: 12px;
  color: white;
  padding: 0px 20px 0;
}
.overlay-text {
  font-size: 14px;
  color: white;
  padding: 0 20px 10px;
  text-align: justify;
}
</style>
