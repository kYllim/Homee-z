<script setup lang="ts">
import { ref } from "vue";
import type { Ref } from "vue";

const IsOpen: Ref<boolean> = ref(false);
</script>

<template>
  <nav
    class="flex flex-row justify-center content-center py-2 px-4 relative lg:justify-between lg:px-[58px] lg:py-6"
  >
    <!-- Bouton menu burger -->
    <i
      v-show="!IsOpen"
      @click="IsOpen = !IsOpen"
      class="pi pi-bars absolute top-4 left-4 text-xl lg:hidden important"
    ></i>
    <i
      v-show="IsOpen"
      @click="IsOpen = !IsOpen"
      class="pi pi-times absolute top-4 left-4 text-xl lg:hidden impotant"
    ></i>

    <h1 class="font-bold text-[22px] lg:text-[50px]">homee'z</h1>
    <!-- Naviation Desktop -->
    <div
      class="hidden lg:flex flex-row gap-[22px] justify-center content-center items-center"
    >
      <a href="#" class="text-[22px] font-semibold">se connecter</a>
      <a
        href="#"
        class="text-[22px] bg-green_pastel rounded-[20px] px-10 py-5 text-white font-semibold"
        >créer un compte</a
      >
    </div>
  </nav>
  <!-- Naviation mobile -->
  <div
    :class="IsOpen ? 'flex flex-col border-2 border-amber-400 px-2' : 'hidden'"
  >
    <a href="#">se connecter</a>
    <a href="#">créer un compte</a>
  </div>
</template>
