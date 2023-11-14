<template>
  <div class="q-pa-md row items-start q-gutter-md" v-if="post.id > 0">
    <q-card class="my-card" flat bordered>
      <q-item>
        <div class="q-pa-md q-gutter-sm">
          <q-breadcrumbs>
            <q-breadcrumbs-el
              style="cursor: pointer"
              icon="navigation"
              label="back"
              @click="$router.back()"
            />
            <q-breadcrumbs-el :label="post.title" icon="widgets" />
          </q-breadcrumbs>
        </div>
      </q-item>
      <q-separator />
      <q-item>
        <q-item-section avatar>
          <q-avatar>
            <img :src="post.userAvatar" />
          </q-avatar>
        </q-item-section>

        <q-item-section>
          <q-item-label>{{ post.username }}</q-item-label>
          <q-item-label caption>
            {{ post.userEmail }}
          </q-item-label>
        </q-item-section>
        <q-btn class="bg-primary text-white" @click="$router.back()"
          >&leftarrow; Go Back</q-btn
        >
      </q-item>

      <q-separator />

      <q-card-section horizontal>
        <q-card-section>
          <q-img :ratio="16 / 9" :src="post.image" />
          {{ post.description }}
        </q-card-section>

        <q-separator vertical />

        <q-card-section class="col-4">
          <p class="text-primary">
            Title: <span class="text-grey-7">{{ post.title }}</span>
          </p>
          <p class="text-primary">
            Created At: <span class="text-grey-7">{{ post.created_at }}</span>
          </p>
          <p class="text-primary">
            Updated At: <span class="text-grey-7">{{ post.updated_at }}</span>
          </p>
          <q-btn class="full-width bg-primary text-white" icon-right="favorite" @click="like(post_id)">
            <span class="q-pa-sm">{{ `Like ${post.up_vote_count} ` }}</span>
          </q-btn>
        </q-card-section>
      </q-card-section>

      <q-separator />

      <q-card-section
        v-if="post.latitude > 0"
        style="width: 100%; height: 300px"
      >
        <map-view
          class="full-width full-height"
          :latitude="post.latitude"
          :longitude="post.longitude"
          :state="'view'"
        ></map-view>
      </q-card-section>
    </q-card>
  </div>
</template>

<script lang="ts" setup>
import MapView from 'src/components/map/mapView.vue';
import { Post } from 'src/models/post';
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import { useQuasar } from 'quasar';

const $q = useQuasar();
const route = useRoute();
const post_id = ref(route.params.id);

const post = ref({
  id: <number>0,
  username: <string>'',
  userEmail: <string>'',
  userAvatar: <string>'',
  description: <string>'',
  image: <string>'',
  title: <string>'',
  created_at: <string>'',
  updated_at: <string>'',
  up_vote_count: <number>0,
  latitude: <number>0,
  longitude: <number>0,
});

const refresh = (id: number) => {
  Post.postDetail(id).then((response) => {
    post.value.id = response.data.data.id;
    post.value.username = response.data.data.user.name;
    post.value.userEmail = response.data.data.user.email;
    post.value.userAvatar =
      'http://127.0.0.1:8000/' + response.data.data.user.media[0].url;
    post.value.description = response.data.data.description;
    post.value.image =
      'http://127.0.0.1:8000/' + response.data.data.media[0].url;
    post.value.title = response.data.data.title;
    post.value.created_at = response.data.data.created_at;
    post.value.updated_at = response.data.data.updated_at;
    post.value.up_vote_count = response.data.data.up_vote_count;
    post.value.latitude = response.data.data.latitude;
    post.value.longitude = response.data.data.longitude;
  });
};

refresh(post_id.value);

const like = (id: number) => {
  Post.likePost(id).then((response) => {
    if (response.status == 200) {
      refresh(id);
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
</script>
