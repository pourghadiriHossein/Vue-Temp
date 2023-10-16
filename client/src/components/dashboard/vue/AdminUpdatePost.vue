<script setup lang="ts">
import { defineProps, defineEmits, ref, watch } from 'vue';
import MapView from 'src/components/map/mapView.vue';
import { Post } from 'src/models/post';

const props = defineProps({
  modelValue: {
    default: false,
  },
  id: {
    default: 0,
  },
  title: {
    default: 'Update',
  },
  description: {
    default: 'No Description',
  },
  latitude: {
    default: 37.28,
  },
  longitude: {
    default: 49.6,
  },
  refresh: {},
});

const titleError = ref('');
const descriptionError = ref('');
const imageError = ref('');

const titleState = ref(null);
const descriptionState = ref(null);

const updatePostParameter = ref({
  title: '',
  description: '',
  image: undefined,
  latitude: <number>37.28,
  longitude: <number>49.6,
});

watch(props, () => {
  updatePostParameter.value = {
    title: props.title,
    description: props.description,
    image: updatePostParameter.value.image,
    latitude: props.latitude,
    longitude: props.longitude,
  };
});

const emit = defineEmits(['update:model-value']);

const close = () => {
  emit.call(this, 'update:model-value', false);
};
const accepted = () => {
  titleState.value.validate();
  descriptionState.value.validate();

  if (titleState.value.hasError || descriptionState.value.hasError) {
    console.log('please Complete Form Data');
  } else {
    Post.adminUpdatePost(
      props.id,
      updatePostParameter.value.title,
      updatePostParameter.value.description,
      updatePostParameter.value.image,
      updatePostParameter.value.latitude,
      updatePostParameter.value.longitude
    ).then(
      (response) => {
        if (response.status == 200) {
          props.refresh();
          emit.call(this, 'update:model-value', false);
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
        <div class="text-h6">Update Post {{ props.title }}</div>
      </q-card-section>
      <q-card-section class="q-pt-none">
        <q-input
        ref="titleState"
          dense
          v-model:model-value="updatePostParameter.title"
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
          v-model:model-value="updatePostParameter.description"
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
        filled
          bottom-slots
          v-model:model-value="updatePostParameter.image"
          label="Post Image"
          counter
          :error="imageError.length > 0"
          :error-message="imageError"
        >
          <template v-slot:prepend>
            <q-icon name="cloud_upload" @click.stop.prevent />
          </template>
          <template v-slot:append>
            <q-icon
              name="close"
              @click.stop.prevent="updatePostParameter.image = null"
              class="cursor-pointer"
            />
          </template>
          <template v-slot:hint> File Size </template>
        </q-file>
      </q-card-section>
      <map-view
        v-model:latitude="updatePostParameter.latitude"
        v-model:longitude="updatePostParameter.longitude"
        :state="'update'"
      ></map-view>
      <q-card-actions align="right" class="text-primary">
        <q-btn color="red" icon-right="close" label="Cancel" @click="close" />
        <q-btn
          color="light-blue-8"
          icon-right="update"
          label="Update"
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
