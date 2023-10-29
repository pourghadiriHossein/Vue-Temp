<template>
  <q-layout view="hHh LpR fFf">
    <q-header elevated :class="`text-white ${navBarClass}`">
      <q-toolbar>
        <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />
        Menu

        <q-toolbar-title>
          <q-avatar>
            <img src="../../image/logo.png" />
          </q-avatar>
        </q-toolbar-title>
          <div class="q-gutter-md" style="width: 200px">
            <q-select v-model="navBarSelect" :options="navBarOptions" label="Nav Bar Style" />
          </div>
        <q-toggle
          v-model="darkMode"
          color="black"
          @click="themeMode"
        />
        Profile
        <q-btn dense flat round icon="menu" @click="toggleRightDrawer" />
      </q-toolbar>
    </q-header>

    <q-drawer show-if-above v-model="leftDrawerOpen" side="left" elevated>
      <!-- drawer content -->
      <q-list separator v-if="profile.role == 'admin'">
        <q-item
          v-for="(item, index) in accessMenu"
          :key="index"
          :to="{ name: item.route }"
          v-ripple
          clickable
        >
          <q-avatar><q-icon :name="item.icon"></q-icon></q-avatar>
          <q-item-section>
            <q-item-label class="q-ml-sm"> {{ item.name }} </q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
      <q-list separator v-else>
        <q-item
          v-for="(item, index) in accessMenu.slice(0, 2)"
          :key="index"
          :to="{ name: item.route }"
          v-ripple
          clickable
        >
          <q-avatar><q-icon :name="item.icon"></q-icon></q-avatar>
          <q-item-section>
            <q-item-label class="q-ml-sm"> {{ item.name }} </q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-drawer show-if-above v-model="rightDrawerOpen" side="right" elevated>
      <!-- drawer content -->
      <div class="avatarBox row items-center justify-center">
        <q-avatar size="150px" class="overlapping">
          <q-img :src="profile.avatar"></q-img>
        </q-avatar>
      </div>
      <div class="q-pa-md row items-start q-gutter-md justify-center">
        <q-card flat bordered class="my-card text-center">
          <q-card-section>
            <div class="text-h3">Profile</div>
          </q-card-section>
          <q-separator inset />

          <q-card-section class="q-pt-none">
            <div class="text-h6">User Name</div>
            {{ profile.username }}
          </q-card-section>

          <q-separator inset />

          <q-card-section class="q-pt-none">
            <div class="text-h6">E-Mail</div>
            {{ profile.email }}
          </q-card-section>
        </q-card>
      </div>
      <div class="q-pa-md q-gutter-sm text-center">
        <q-btn
          class="full-width"
          label="Update"
          color="light-blue-8"
          @click="prompt = true"
        />
        <q-btn class="full-width" label="Logout" color="red" @click="logout" />

        <q-dialog v-model="prompt" persistent>
          <q-card style="width: 350px">
            <q-card-section>
              <div class="text-h6">Update Profile</div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <q-input
                ref="nameState"
                dense
                v-model:model-value="profile.username"
                @keyup.enter="prompt = false"
                label="Your User Name"
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
            </q-card-section>
            <q-card-section class="q-pt-none">
              <q-input
                ref="emailState"
                dense
                v-model:model-value="profile.email"
                @keyup.enter="prompt = false"
                label="Your E-Mail"
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
            </q-card-section>
            <q-card-section class="q-pt-none">
              <q-input
                ref="passwordState"
                dense
                @keyup.enter="prompt = false"
                label="Your Password"
                :rules="[
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
            </q-card-section>
            <q-card-section class="q-pt-none">
              <q-file
                ref="avatarState"
                filled
                bottom-slots
                v-model="profile.newAvatar"
                label="Label"
                counter
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
                    @click.stop.prevent="model = null"
                    class="cursor-pointer"
                  />
                </template>

                <template v-slot:hint> File Size </template>
              </q-file>
            </q-card-section>
            <q-card-actions align="right" class="text-primary">
              <q-btn flat label="Cancel" v-close-popup />
              <q-btn flat label="Submit" @click="update()" />
            </q-card-actions>
          </q-card>
        </q-dialog>
      </div>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { ref, watch } from 'vue';
import { accessMenu } from 'src/components/dashboard/ts/menuListComponent';
import { profile, userData } from 'src/components/dashboard/ts/profileComponent';
import { useAuthStore } from 'src/stores/auth-store';
import { useRouter } from 'vue-router';
import { User } from 'src/models/user';
import { useQuasar } from 'quasar'

export default {
  setup() {
    const $q = useQuasar()

    const darkMode = ref(false);
    const leftDrawerOpen = ref(false);
    const rightDrawerOpen = ref(false);
    const authStore = useAuthStore();
    const router = useRouter();
    const prompt = ref(false);

    const nameError = ref('');
    const emailError = ref('');
    const passwordError = ref('');
    const avatarError = ref('');

    const nameState = ref(null);
    const emailState = ref(null);
    const passwordState = ref(null);
    const avatarState = ref(null);

    userData();

    const navBarStyle = ref({
      RGB: 'RGB',
      Primary: 'Primary',
      Dark: 'Dark',
    });

    const  navBarSelect = ref('Choose Your Style')
    const  navBarOptions = [
      'RGB', 'Primary', 'Dark'
    ]
    const navBarClass = ref('default');
    const checkStyle = watch(navBarSelect,()=>{
      if (navBarSelect.value === 'RGB') {
        navBarClass.value = navBarStyle.value.RGB
      } else if (navBarSelect.value === 'Primary') {
        navBarClass.value = navBarStyle.value.Primary
      }
      else if (navBarSelect.value === 'Dark') {
        navBarClass.value = navBarStyle.value.Dark
      }
      console.log(navBarSelect.value);
    });
    return {

      themeMode(){
        if($q.dark.mode == false){
          $q.dark.set(true)
        }else{
          $q.dark.set(false)
        }
      },
      navBarClass,
      navBarSelect,
      navBarOptions,
      checkStyle,
      darkMode,
      nameError,
      emailError,
      passwordError,
      avatarError,

      nameState,
      emailState,
      passwordState,
      avatarState,

      userData,
      accessMenu,
      profile,
      prompt,
      model: ref(null),
      leftDrawerOpen,
      toggleLeftDrawer() {
        leftDrawerOpen.value = !leftDrawerOpen.value;
      },

      rightDrawerOpen,
      toggleRightDrawer() {
        rightDrawerOpen.value = !rightDrawerOpen.value;
      },
      logout() {
        authStore.logout();
        router.replace({ name: 'login' });
      },
      update() {
        nameState.value.validate();
        emailState.value.validate();

        if (
          nameState.value.hasError ||
          emailState.value.hasError
        ) {
          console.log('please Complete Form Data');
        } else {
          User.updateProfile(
            profile.value.id,
            profile.value.username,
            profile.value.email,
            profile.value.password,
            profile.value.newAvatar
          ).then(
            (response) => {
              if (response.status == 200) {
                prompt.value = !prompt.value;
                userData();
                setTimeout(() => {
                $q.notify({
                  message: 'Profile Update Accepted.',
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
      },
    };
  },
};
</script>
<style>
.q-layout {
  background-image: url(../../image/loginWallpaper.jpg);
  background-repeat: no-repeat;
  background-size: cover;
}
.default {
  background-image: linear-gradient(
    to right,
    rgb(5, 77, 139),
    rgb(2, 136, 209),
    rgb(245, 246, 240),
    rgb(30, 29, 71),
    rgb(0, 0, 28),
    rgb(30, 29, 71),
    rgb(245, 246, 240),
    rgb(2, 136, 209),
    rgb(5, 77, 139)
  );
}
.avatarBox {
  background-image: radial-gradient(
    rgb(245, 246, 240),
    rgb(2, 136, 209),
    rgb(5, 77, 139)
  );
  width: 100%;
  height: 300px;
  box-shadow: 0px 3px 9px rgba(0, 0, 0, 0.9);
}
.RGB {
  animation: RGB 5s alternate infinite linear;
}
@keyframes RGB {
  0% {
    background-color: red;
  }
  50% {
    background-color: green;
  }
  100% {
    background-color: blue;
  }
}
.Primary {
  animation: Primary 5s alternate infinite linear;
}
@keyframes Primary {
  0% {
    background-color: #caf0f8;
  }
  12% {
    background-color: #ade8f4;
  }
  24% {
    background-color: #90e0ef;
  }
  36% {
    background-color: #48cae4;
  }
  48% {
    background-color: #00b4d8;
  }
  60% {
    background-color: #0096c7;
  }
  72% {
    background-color: #0077b6;
  }
  84% {
    background-color: #023e8a;
  }
  100% {
    background-color: #03045e;
  }
}

.Dark {
  animation: Dark 5s alternate infinite linear;
}
@keyframes Dark {
  0% {
    background-color: #f8f9fa;
  }
  12% {
    background-color: #e9ecef;
  }
  24% {
    background-color: #dee2e6;
  }
  36% {
    background-color: #ced4da;
  }
  48% {
    background-color: #adb5bd;
  }
  60% {
    background-color: #6c757d;
  }
  72% {
    background-color: #495057;
  }
  84% {
    background-color: #343a40;
  }
  100% {
    background-color: #212529;
  }
}
</style>
