<template>
  <q-page class="window-height window-width row justify-center items-center">
    <div class="column">
      <div class="row">
        <h5 class="text-h5 text-white q-my-md">Welcome To Open World</h5>
      </div>
      <div class="row">
        <q-card square bordered class="q-pa-lg">
          <q-card-section>
            <q-form class="q-gutter-md">
              <q-input
                ref="nameState"
                square
                filled
                clearable
                v-model:model-value="newUser.name"
                type="text"
                label="First Name & Last Name"
                :rules="[
                  (val) => !!val || 'Required',
                  (val) => val.length > 3 || 'Please use minimum 4 character',
                  (val) =>
                    val.length <= 100 || 'Please use maximum 100 character',
                ]"
                lazy-rules
                :error="nameError.length > 0"
                :error-message="nameError"
              />
              <q-input
                ref="emailState"
                square
                filled
                clearable
                v-model:model-value="newUser.email"
                type="email"
                label="Email"
                :rules="[
                  (val) => !!val || 'Required',
                  (val) => val.length > 3 || 'Please use minimum 4 character',
                  (val) =>
                    val.length <= 100 || 'Please use maximum 100 character',
                  (val, rules) =>
                    rules.email(val) || 'Please enter a valid email address',
                ]"
                lazy-rules
                :error="emailError.length > 0"
                :error-message="emailError"
              />
              <q-input
                ref="passwordState"
                square
                filled
                clearable
                v-model:model-value="newUser.password"
                type="password"
                label="Password"
                :rules="[
                  (val) => !!val || 'Required',
                  (val) => val.length > 3 || 'Please use minimum 4 character',
                  (val) =>
                    val.length <= 100 || 'Please use maximum 100 character',
                  (val) =>
                    /[a-z]/.test(val) ||
                    'Enter lower case character in your password',
                  (val) =>
                    /[A-Z]/.test(val) ||
                    'Enter upper case character in your password',
                  (val) => /[0-9]/.test(val) || 'Enter number in your password',
                  (val) =>
                    /[~!@#$%^&*]/.test(val) || 'Enter symbol in your password',
                ]"
                lazy-rules
                :error="passwordError.length > 0"
                :error-message="passwordError"
                hint="Enter your password that includes numbers, uppercase and lowercase letters, and symbols"
              />
              <q-file
                ref="avatarState"
                filled
                bottom-slots
                v-model:model-value="newUser.avatar"
                label="Avatar"
                counter
                :rules="[(val) => !!val || 'Required']"
                lazy-rules
                :error="avatarError.length > 0"
                :error-message="avatarError"
              >
                <template v-slot:prepend>
                  <q-icon name="cloud_upload" @click.stop.prevent />
                </template>
                <template v-slot:append>
                  <q-icon
                    name="close"
                    @click.stop.prevent="newUser.avatar = null"
                    class="cursor-pointer"
                  />
                </template>

                <template v-slot:hint> File Size </template>
              </q-file>
            </q-form>
          </q-card-section>
          <q-card-actions class="q-px-md">
            <q-btn
              @click="register"
              unelevated
              color="light-blue-8"
              size="lg"
              class="full-width"
              label="Register"
            />
            <q-btn
              @click="login"
              unelevated
              color="red"
              size="lg"
              class="full-width q-ma-sm"
              label="Login"
            />
          </q-card-actions>
          <q-card-section class="text-center q-pa-none"> </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { User } from 'src/models/user';
import { useQuasar } from 'quasar';

export default {
  setup() {
    const $q = useQuasar();

    const nameError = ref('');
    const emailError = ref('');
    const passwordError = ref('');
    const avatarError = ref('');

    const nameState = ref(null);
    const emailState = ref(null);
    const passwordState = ref(null);
    const avatarState = ref(null);

    const router = useRouter();
    const newUser = ref({
      name: '',
      email: '',
      password: '',
      avatar: [],
    });

    return {
      nameError,
      emailError,
      passwordError,
      avatarError,

      nameState,
      emailState,
      passwordState,
      avatarState,

      newUser,
      login() {
        router.replace({ name: 'login' });
      },
      register() {
        nameState.value.validate();
        emailState.value.validate();
        passwordState.value.validate();
        avatarState.value.validate();

        if (
          nameState.value.hasError ||
          emailState.value.hasError ||
          passwordState.value.hasError ||
          avatarState.value.hasError
        ) {
          console.log('please Complete Form Data');
        } else {
          User.register(
            newUser.value.name,
            newUser.value.email,
            newUser.value.password,
            newUser.value.avatar
          ).then(
            (response) => {
              if (response.status == 200) {
                if (response.data.errors) {
                  setTimeout(() => {
                    router.replace({ name: 'login' });
                  }, 2000);
                  setTimeout(() => {
                    $q.notify({
                      message: 'Register Accepted.',
                      color: 'positive',
                      position: 'bottom-right',
                    });
                  }, 1000);
                }
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
      },
    };
  },
};
</script>

<style>
.q-page {
  background-image: url(../../image/loginWallpaper.jpg);
  background-repeat: no-repeat;
  background-size: cover;
}
.q-card {
  width: 400px;
}
</style>
