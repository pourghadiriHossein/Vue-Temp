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
                @keyup.enter="login"
                square
                filled
                clearable
                v-model:model-value="guestUser.username"
                type="email"
                label="Email"
                :rules="[
                  (val) => !!val || 'Required',
                  (val) => val.length > 3 || 'Please use minimum 4 character',
                  (val) => val.length <= 100 || 'Please use maximum 100 character',
                  (val, rules) =>
                    rules.email(val) || 'Please enter a valid email address',
                ]"
                :error="errorState"
                :error-message="errorMessage"
              />
              <q-input
                @keyup.enter="login"
                square
                filled
                clearable
                v-model:model-value="guestUser.password"
                type="password"
                label="Password"
                :rules="[(val) => !!val || 'Required']"
                :error="errorState"
                :error-message="errorMessage"
              />
            </q-form>
          </q-card-section>
          <q-card-actions class="q-px-md">
            <q-btn
              @click="login"
              unelevated
              color="light-blue-8"
              size="lg"
              class="full-width"
              label="login"
            />
            <q-btn
              @click="register"
              unelevated
              color="red"
              size="lg"
              class="full-width q-ma-sm"
              label="Register"
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
import { useAuthStore } from 'src/stores/auth-store';

export default {
  setup() {
    const errorState = ref(false);
    const errorMessage = ref('');
    const router = useRouter();
    const guestUser = ref({
      username: '',
      password: '',
    });
    const authStore = useAuthStore();
    return {
      errorMessage,
      errorState,
      guestUser,
      login() {
        authStore
          .authenticate(guestUser.value.username, guestUser.value.password)
          .then(
            () => {
              router.replace({ name: 'dashboard' });
            },
            (error) => {
              if(error.response.data.message){
                errorMessage.value = error.response.data.message;
                errorState.value = true;
              }
              setTimeout(()=>{
                errorMessage.value = '';
                errorState.value = false;
              },5000);
              console.log(
                `No Internet, Connection Lost because server not serve!!!\n${error}`
              );
            }
          );
      },
      register() {
        router.replace({ name: 'register' });
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
