<script lang="ts" setup>
import { defineProps, defineEmits, ref } from 'vue';
import MapView from 'src/components/map/mapView.vue';
import { Post } from 'src/models/post';
import { useQuasar } from 'quasar';

const $q = useQuasar();

const props = defineProps({
  modelValue: {
    default: false,
  },
  img: {
    default: <any>null,
  },
  title: {
    default: '',
  },
  description: {
    default: '',
  },
  refresh: {},
});

const titleError = ref('');
const descriptionError = ref('');
const imageError = ref('');

const titleState = ref(null);
const descriptionState = ref(null);
const imageState = ref(null);

const createPostParameter = ref({
  title: '',
  description: '',
  image: undefined,
  latitude: <number>37.28,
  longitude: <number>49.6,
});

const emit = defineEmits(['update:model-value']);

const close = () => {
  emit.call(this, 'update:model-value', false);
};

const accepted = () => {
  titleState.value.validate();
  descriptionState.value.validate();
  imageState.value.validate();

  if (
    titleState.value.hasError ||
    descriptionState.value.hasError ||
    imageState.value.hasError
  ) {
    console.log('please Complete Form Data');
  } else {
    Post.createPost(
      createPostParameter.value.title,
      createPostParameter.value.description,
      createPostParameter.value.image,
      createPostParameter.value.latitude,
      createPostParameter.value.longitude
    ).then(
      (response) => {
        if (response.status == 200) {
          props.refresh();
          emit.call(this, 'update:model-value', false);
          setTimeout(() => {
            $q.notify({
              message: 'Post Created.',
              color: 'positive',
              position: 'bottom-right',
            });
          }, 1000);
        }
      },
      (reject) => {
        if (reject.response.status != 200) {
          if (reject.response.data.errors) {
            titleError.value =
              reject.response.data.errors?.title?.toString() ?? '';
            descriptionError.value =
              reject.response.data.errors?.description?.toString() ?? '';
            imageError.value =
              reject.response.data.errors?.image?.toString() ?? '';
            setTimeout(() => {
              titleError.value = '';
              descriptionError.value = '';
              imageError.value = '';
            }, 5000);
          }
        }
      }
    );
  }
};
</script>

<template>
  <q-dialog :model-value="modelValue" persistent>
    <q-card style="min-width: 350px">
      <q-card-section>
        <div class="text-h6">Create New Post</div>
      </q-card-section>
      <q-card-section class="q-pt-none">
        <q-input
          ref="titleState"
          dense
          v-model:model-value="createPostParameter.title"
          label="Enter Your Title"
          :rules="[
            (val) => !!val || 'Required',
            (val) => val.length > 3 || 'Please use minimum 4 character',
            (val) => val.length <= 100 || 'Please use maximum 100 character',
          ]"
          lazy-rules
          :error="titleError.length > 0"
          :error-message="titleError"
        />
      </q-card-section>
      <q-card-section class="q-pt-none">
        <q-input
          ref="descriptionState"
          type="textarea"
          dense
          v-model:model-value="createPostParameter.description"
          label="Enter Your Description"
          :rules="[
            (val) => !!val || 'Required',
            (val) => val.length > 3 || 'Please use minimum 4 character',
            (val) =>
              val.length <= 10000 || 'Please use maximum 10000 character',
          ]"
          lazy-rules
          :error="descriptionError.length > 0"
          :error-message="descriptionError"
        />
      </q-card-section>
      <q-card-section class="q-pt-none">
        <q-file
          ref="imageState"
          filled
          bottom-slots
          v-model:model-value="createPostParameter.image"
          label="Post Image"
          counter
          lazy-rules
          :rules="[(val) => !!val || 'Required']"
          :error="imageError.length > 0"
          :error-message="imageError"
        >
          <template v-slot:prepend>
            <q-icon name="cloud_upload" @click.stop.prevent />
          </template>
          <template v-slot:append>
            <q-icon
              name="close"
              @click.stop.prevent="createPostParameter.image = null"
              class="cursor-pointer"
            />
          </template>
          <template v-slot:hint> File Size </template>
        </q-file>
      </q-card-section>
      <map-view
        v-model:latitude="createPostParameter.latitude"
        v-model:longitude="createPostParameter.longitude"
        :state="'update'"
      ></map-view>
      <q-card-actions align="right" class="text-primary">
        <q-btn color="red" icon-right="close" label="Cancel" @click="close" />
        <q-btn
          color="light-blue-8"
          icon-right="create"
          label="Create"
          @click="accepted"
        />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<style>
.confirm-dialog {
  width: 95%;
  max-width: 400px;
}
</style>
