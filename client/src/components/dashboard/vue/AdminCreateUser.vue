<script setup lang="ts">
import { defineProps, defineEmits, ref } from 'vue';
import { User } from 'src/models/user';
import { useQuasar } from 'quasar';

const $q = useQuasar();

const props = defineProps({
  modelValue: {
    default: false,
  },
  id: {
    default: 0,
  },
  username: {
    default: '',
  },
  email: {
    default: '',
  },
  password: {
    default: '',
  },
  options: {
    default: ['admin', 'user'],
  },
  refresh: {},
});
const nameError = ref('');
const emailError = ref('');
const passwordError = ref('');
const avatarError = ref('');
const choiceError = ref('');

const nameState = ref(null);
const emailState = ref(null);
const passwordState = ref(null);
const avatarState = ref(null);
const choiceState = ref(null);

const choice = ref(null);

const createUserParameter = ref({
  username: props.username,
  email: props.username,
  password: props.username,
  avatar: undefined,
});

const emit = defineEmits(['update:model-value']);

const close = () => {
  emit.call(this, 'update:model-value', false);
};
const accepted = () => {
  nameState.value.validate();
  emailState.value.validate();
  passwordState.value.validate();
  avatarState.value.validate();
  choiceState.value.validate();

  if (
    nameState.value.hasError ||
    emailState.value.hasError ||
    passwordState.value.hasError ||
    avatarState.value.hasError ||
    choiceState.value.hasError
  ) {
    console.log('please Complete Form Data');
  } else {
    User.createUserByAdmin(
      createUserParameter.value.username,
      createUserParameter.value.email,
      createUserParameter.value.password,
      createUserParameter.value.avatar,
      choice.value
    ).then(
      (response) => {
        if (response.status == 200) {
          props.refresh();
          emit.call(this, 'update:model-value', false);
          setTimeout(() => {
            $q.notify({
              message: 'User Created.',
              color: 'positive',
              position: 'bottom-right',
            });
          }, 1000);
        }
      },
      (reject) => {
        if (reject.response.status != 200) {
          if (reject.response.data.errors) {
            nameError.value =
              reject.response.data.errors?.name?.toString() ?? '';
            emailError.value =
              reject.response.data.errors?.email?.toString() ?? '';
            passwordError.value =
              reject.response.data.errors?.password?.toString() ?? '';
            avatarError.value =
              reject.response.data.errors?.avatar?.toString() ?? '';
            setTimeout(() => {
              nameError.value = '';
              emailError.value = '';
              passwordError.value = '';
              avatarError.value = '';
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
        <div class="text-h6">Create New User</div>
      </q-card-section>

      <q-card-section class="q-pt-none">
        <q-input
          ref="nameState"
          dense
          @keyup.enter="modelValue"
          v-model:model-value="createUserParameter.username"
          label="Enter Your User Name"
          :rules="[
            (val) => !!val || 'Required',
            (val) => val.length > 3 || 'Please use minimum 4 character',
            (val) => val.length <= 100 || 'Please use maximum 100 character',
          ]"
          lazy-rules
          :error="nameError.length > 0"
          :error-message="nameError"
        />
      </q-card-section>
      <q-card-section class="q-pt-none">
        <q-input
          ref="emailState"
          dense
          @keyup.enter="modelValue"
          v-model:model-value="createUserParameter.email"
          label="Enter Your E-Mail"
          :rules="[
            (val) => !!val || 'Required',
            (val) => val.length > 3 || 'Please use minimum 4 character',
            (val) => val.length <= 100 || 'Please use maximum 100 character',
            (val, rules) =>
              rules.email(val) || 'Please enter a valid email address',
          ]"
          lazy-rules
          :error="emailError.length > 0"
          :error-message="emailError"
        />
      </q-card-section>
      <q-card-section class="q-pt-none">
        <q-input
          ref="passwordState"
          type="password"
          dense
          @keyup.enter="modelValue"
          v-model:model-value="createUserParameter.password"
          label="Enter Your Password"
          :rules="[
            (val) => !!val || 'Required',
            (val) => val.length > 3 || 'Please use minimum 4 character',
            (val) => val.length <= 100 || 'Please use maximum 100 character',
            (val) =>
              /[a-z]/.test(val) ||
              'Enter lower case character in your password',
            (val) =>
              /[A-Z]/.test(val) ||
              'Enter upper case character in your password',
            (val) => /[0-9]/.test(val) || 'Enter number in your password',
            (val) => /[~!@#$%^&*]/.test(val) || 'Enter symbol in your password',
          ]"
          lazy-rules
          :error="passwordError.length > 0"
          :error-message="passwordError"
          hint="Enter your password that includes numbers, uppercase and lowercase letters, and symbols"
        />
      </q-card-section>
      <q-card-section class="q-pt-none">
        <q-file
          ref="avatarState"
          filled
          bottom-slots
          v-model:model-value="createUserParameter.avatar"
          label="Avatar"
          counter
          lazy-rules
          :rules="[(val) => !!val || 'Required']"
          :error="avatarError.length > 0"
          :error-message="avatarError"
        >
          <template v-slot:prepend>
            <q-icon name="cloud_upload" @click.stop.prevent />
          </template>
          <template v-slot:append>
            <q-icon
              name="close"
              @click.stop.prevent="createUserParameter.avatar = null"
              class="cursor-pointer"
            />
          </template>

          <template v-slot:hint> File Size </template>
        </q-file>
      </q-card-section>
      <q-card-section>
        <q-select
          ref="choiceState"
          v-model="choice"
          :options="options"
          label="Role"
          :rules="[(val) => !!val || 'Required']"
          :error="choiceError.length > 0"
          :error-message="choiceError"
        />
      </q-card-section>
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
